{{--
  -- Show media list for invite form in a column
  -- PARAMS:
  -- $collection  => collection of media
  -- $prefixId    => prefix name for id
  -- $limitString => number of characters to display, unset to display all
  --}}
@foreach ($collection as $medium)
  <li>
    <label class="checkbox-default mr5" for="{{ $prefixId }}-{{ $medium->id }}">
      <input type="checkbox" id="{{ $prefixId }}-{{ $medium->id }}" name="media[]" class="medium" value="{{ $medium->id }}">
      <span class="ico-checkbox"></span>
    </label>
    <label for="{{ $prefixId }}-{{ $medium->id }}">
      <a title="{{ $medium->title }}" target="_blank" href="{{ route('media::show', ['id' => $medium->id]) }}">
        {{ isset($limitString) && $limitString > 0 ? limitString($medium->title, $limitString) : $medium->title }}
      </a>
    </label>
  </li>
@endforeach
