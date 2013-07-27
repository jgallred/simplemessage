<?php

namespace Jgallred\Simplemessage\Messaging\Typed;

use Illuminate\Support\MessageBag;
use Countable;
use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\JsonableInterface;
use Illuminate\Support\Contracts\MessageProviderInterface;

/**
 * I tried to extend MessageBag, but all I would get are autoload errors
 */
class Messages implements ArrayableInterface, Countable, JsonableInterface, MessageProviderInterface {//extends MessageBag {

    /**
     * Default key for messages that don't have a type
     *
     * @var string
     */

    const default_type = ':default:';

    /**
     * All of the registered messages.
     *
     * @var array
     */
    protected $messages = array();

    /**
     * Default format for message output.
     *
     * @var string
     */
    protected $format = ':message';

    /**
     * Add a typed message to the collector
     *
     * <code>
     *    // Add a message with success type
     *    $messages->add('Your item has been added.', 'success');
     *
     *    // Add a message with no type
     *    $messages->add('Your item will be held for 30 days for review.');
     * </code>
     *
     * @param string $message
     * @param string $type
     */
    public function add_typed($message, $type = null) {
        $entry = new Entry($message, $type);

        // we don't want nulls as array keys. so if we haven't been
        // given a type, we'll use a default type string instead.
        $key = is_null($type) ? self::default_type : $type;

        if ($this->unique($key, $message))
            $this->messages[$key][] = $entry;
    }

    /**
     * Add a message. This method is kept for consistency with
     * the Laravel\Messages class, but it's recommended that you use
     * add_typed() instead.
     *
     * @param string $key
     * @param string $message
     * @see  Laravel\Messaging\Messages::add
     */
    public function add($key, $message) {
        $this->add_typed($message, $key);
    }

    protected function unique($key, $message) {
        if (!isset($this->messages[$key]))
            return true;

        // we iterate over all messages of the given type (key)
        // and if we find one that has the same text (message)
        // as the one given, then we don't have a unique message.
        foreach ($this->messages[$key] as $entry) {
            if ($entry->text == $message)
                return false;
        }

        return true;
    }

    /**
     * Determine if messages exist for a given key.
     *
     * <code>
     *    // Is there a message for the e-mail attribute
     *    return $messages->has('email');
     *
     *    // Is there a message for the any attribute
     *    echo $messages->has();
     * </code>
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key = null) {
        return $this->first($key) !== null;
    }

    /**
     * Get the first message from the container for a given key.
     *
     * <code>
     *    // Echo the first message out of all messages.
     *    echo $messages->first();
     *
     *    // Echo the first message for the e-mail attribute
     *    echo $messages->first('success');
     *
     *    // Format the first message for the e-mail attribute
     *    echo $messages->first('success', '<p>:message</p>');
     * </code>
     *
     * @param  string  $key
     * @param  string  $format
     * @return Entry
     */
    public function first($key = null, $format = null) {
        $messages = $this->get($key, $format);

        $message = (count($messages) > 0) ? $messages[0] : '';

        return $message instanceof Entry ? $message : null;
    }

    /**
     * Get all of the messages for every type in the container.
     *
     * <code>
     *    // Get all of the messages in the collector
     *    $all = $messages->all();
     *
     *    // Format all of the messages in the collector
     *    $all = $messages->all('<p class=":type">:message</p>');
     * </code>
     *
     * @param  string  $format
     * @return array
     */
    public function all($format = null) {
        $format = ($format === null) ? $this->format : $format;

        return $this->flatten($format);
    }

    /**
     * Create a one-dimensional array of message entries
     *
     * @param  string $format
     * @return array
     */
    protected function flatten($format = null) {
        $flat = array();

        foreach ($this->messages as $entries) {
            if ($format != null)
                $entries = $this->transform($entries, $format);

            $flat = array_merge($flat, $entries);
        }

        return $flat;
    }

    /**
     * Format an array of messages.
     *
     * @param  array   $messages
     * @param  string  $format
     * @return array
     */
    protected function transform($messages, $format) {
        $messages = (array) $messages;
        $transformed = array();

        // we iterate over the entry array and make a formatted
        // copy of each entry, which we place in the transformed
        // array
        foreach ($messages as $entry) {
            $text = $format;
            $text = str_replace(':message', $entry->text, $text);
            $text = str_replace(':type', $entry->type, $text);

            $transformed[] = new Entry($text, $entry->type);
        }

        return $transformed;
    }


    /** These methods were imported from MessageBag, since I can't seem to extend it
     *
     */

    public function __construct(array $messages = array())
    {
        foreach ($messages as $key => $value)
        {
            $this->messages[$key] = (array) $value;
        }
    }

    public function merge(array $messages)
    {
        $this->messages = array_merge_recursive($this->messages, $messages);

        return $this;
    }

    protected function isUnique($key, $message)
    {
        $messages = (array) $this->messages;

        return ! isset($messages[$key]) or ! in_array($message, $messages[$key]);
    }

    public function get($key, $format = null)
    {
        $format = $this->checkFormat($format);

        // If the message exists in the container, we will transform it and return
        // the message. Otherwise, we'll return an empty array since the entire
        // methods is to return back an array of messages in the first place.
        if (array_key_exists($key, $this->messages))
        {
            return $this->transform($this->messages[$key], $format, $key);
        }

        return array();
    }

    protected function checkFormat($format)
    {
        return ($format === null) ? $this->format : $format;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getMessageBag()
    {
        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($format = ':message')
    {
        $this->format = $format;

        return $this;
    }

    public function isEmpty()
    {
        return ! $this->any();
    }

    public function any()
    {
        return $this->count() > 0;
    }

    public function count()
    {
        return count($this->messages);
    }

    public function toArray()
    {
        return $this->getMessages();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        return $this->toJson();
    }

}