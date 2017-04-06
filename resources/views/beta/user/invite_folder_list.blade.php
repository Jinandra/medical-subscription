{{--
  -- Show folder list for invite form in a column
  -- PARAMS:
  -- $collection  => collection of folder
  -- $prefixId    => prefix name for id
  -- $limitString => number of characters to display, unset to display all
  --}}
@foreach ($collection as $folder)
  <li>
    <label class="checkbox-default mr5" for="{{ $prefixId }}-{{ $folder->id }}">
      <input type="checkbox" id="{{ $prefixId }}-{{ $folder->id }}" name="folders[]" class="folder" value="{{ $folder->id }}">
      <span class="ico-checkbox"></span>
    </label>
    <label for="{{ $prefixId }}-{{ $folder->id }}">
      <a title="{{ $folder->name }}" target="_blank" href="{{ route('folder::show', ['id' => $folder->id]) }}">
        {{ isset($limitString) && $limitString > 0 ? limitString($folder->name, $limitString) : $folder->name }}
      </a>
    </label>
  </li>
@endforeach
