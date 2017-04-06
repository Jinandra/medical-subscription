{{--
  -- Generate mapping collections for js
  -- PARAMS:
  -- $collections => collection to generate
  -- $variableName => js variable name that will be generated, default: 'COLLECTIONS_MAPPING'
  -- $showMedia => generate collection's media, default: true
  --}}

<?php $varName = isset($variableName) ? $variableName : 'COLLECTIONS_MAPPING'; ?>
@foreach ($collections as $collection)
  {{ $varName }}['collection-{{ $collection->id }}'] = {
    id: {{ $collection->id }},
    name: '{{ sanitizeToJS($collection->name) }}',
    description: '{{ sanitizeToJS($collection->description) }}',
    original_id: {{ is_null($collection->original_id) ? 'null' : $collection->original_id }},
    category_id: {{ is_null($collection->category_id) ? 'null' : $collection->category_id }},
    @if (!isset($showMedia) || $showMedia !== false)
      media: [
        @foreach ($collection->media as $medium)
          { id: {{ $medium->id }}, title: '{{ sanitizeToJS($medium->title) }}' },
        @endforeach
      ]
    @endif
  };
@endforeach
