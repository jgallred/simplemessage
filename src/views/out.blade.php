
{{-- Outputs all messages using format string --}}
{{-- Shows all general app messages using Twitter Bootstrap styles --}}
@if(isset($messages))
@foreach ($messages->all() as $message)
  <p class="alert {{ $message->type ? 'alert-'.$message->type : '' }}">{{ $message }}</p>
@endforeach
@endif