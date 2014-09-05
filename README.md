#Laravel SimpleMessage#
[![Build Status](https://travis-ci.org/jgallred/simplemessage.svg?branch=master)](https://travis-ci.org/jgallred/simplemessage)

####Preface####
I created this package because I really like the original simplemessage and wanted to use it in a personal project, so I have it working for me. I will happily fix things as I have time, but I'm in no rush. Sorry.

<hr/>

SimpleMessage is a Laravel extension bundle that allows you to easily send messages to your views, centralizing your application's message system and keeping you nice and [DRY][dry].

[dry]: http://en.wikipedia.org/wiki/Don't_repeat_yourself "Don't Repeat Yourself"

If you're familiar with [Laravel's validation error messages][validation], you'll find SimpleMessage follows similar conventions.

[validation]: http://four.laravel.com/docs/validation#error-messages-and-views

    // redirect to a route with a message
    return Redirect::to('item_list')->withMessage('Your item was added.', 'success');

    // retrieve messages
    foreach ($messages->all() as $message)
    {
      echo $message;
    }

##Installation##
* Use v0.5 for Laravel 4.0
* Use v1.0 for Laravel 4.1
* Use v2.0 for Laravel 4.2
* Use v3.0 for Laravel 4.3

You can install SimpleMessage through composer. Just add jgallred/simplemessage to your composer.json file.

Once you've installed the bundle, register it by adding the service provider to your app/config/app.php:

    'providers' => array(
        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        ...
        'Jgallred\Simplemessage\SimplemessageServiceProvider',
    )

That's it. You're all installed and ready to use SimpleMessage.

##Redirecting with Messages##

When you want to send a message to a view via redirect, say to send a success message that notifies the user that an item was added, just use the simple `withMessage()` method.

###Redirect with a message###

    return Redirect::to('item_list')
      ->withMessage('Hey, you should know about this.');

###Redirect with a message and type###

    return Redirect::to('item_list')
      ->withMessage('Your item was added.', 'success');

###Redirect with multiple messages###

    return Redirect::to('item_list')
      ->withMessage('Your item was added.', 'success')
      ->withMessage('Another thing you need to know.', 'info');

##Localized Messages##
If your application is displayed in multiple languages, SimpleMessage provides a `withLangMessage()` method for redirecting with localized messages. Provide the key of the language line you wish to display, just as you would with [Laravel's `Lang::get()` method][lang_get].

[lang_get]: http://four.laravel.com/docs/localization

###Redirect with a localized message###

    return Redirect::to('item_list')
      ->withLangMessage('items.item_added');

###Redirect with a localized message and type###

    return Redirect::to('item_list')
      ->withLangMessage('items.item_added', 'success');

##Making Views with Messages##
You can also pass messages directly to your views.

###Show a view with a message###

    return View::make('item.list')
      ->withMessage('Your item was added.', 'success')
      ->withMessage('Another thing you need to know.', 'info');

    return View::make('item.list')
      ->withLangMessage('items.item_added', 'success');



##Retrieving Messages##

SimpleMessage makes a `$messages` object available to all your views. It works similarly to Laravel's validation `$errors` object.

###Retrieve all messages###

    foreach ($messages->all() as $message)
    {
      echo $message;
    }

###Retrieve all messages of a given type###

    foreach ($messages->get('success') as $message)
    {
      echo $message;
    }

###Retrieve first message of all messages###

    echo $messages->first();

###Retrieve first message of a given type###

    echo $messages->first('success');

###Check if messages of a given type exist###

    if ($messages->has('success'))
    {
      echo $messages->first('success');
    }

##Formatting##

If you're using something like Twitter Bootstrap, or your own CSS styling, you'll appreciate SimpleMessage's message formatting. Just like Laravel's validation errors, SimpleMessage's retrieval methods take an optional format parameter, which allows you to easily format your messages using `:message` and `:type` placeholders.

###Retrieve all messages with formatting###

    foreach ($messages->all('<p class=":type">:message</p>') as $message)
    {
      echo $message;
    }

###Retrieve all messages of a given type with formatting###

    foreach ($messages->get('success', '<p class=":type">:message</p>') as $message)
    {
      echo $message;
    }

###Retrieve first message of a given type with formatting###

    echo $messages->first('success', '<p class=":type">:message</p>');

###Retrieve first message of all messages with formatting###

    echo $messages->first(null, '<p class=":type">:message</p>');

##Message Attributes##

For maximum flexibility, you can access the text and type of a message directly through message attributes.

###Access message attributes###

    foreach ($messages->all() as $message)
    {
      echo 'Message text: '.$message->text.'<br>';
      echo 'Message type: '.$message->type.;
    }

##View Partial##

For convenience, SimpleMessage provides a partial view that outputs all messages using the [Twitter Bootstrap alert class convention][bootstrap]. Just include it in a [Blade][blade] view:

[bootstrap]: http://twitter.github.com/bootstrap/components.html#alerts
[blade]: http://four.laravel.com/docs/templates

    @include('simplemessage::out')

##Unit Tests##

I've tried to test the SimpleMessage bundle as thoroughly as possible. You can run the SimpleMessage tests through phpunit.
