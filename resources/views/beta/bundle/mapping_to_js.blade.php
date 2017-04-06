{{--
  -- Generate mapping ucodes for js
  -- PARAMS:
  -- $ucodes => ucodes to generate
  -- $variableName => js variable name that will be generated, default: 'UCODES_MAPPING'
  --}}
<?php $varName = isset($variableName) ? $variableName : 'UCODES_MAPPING'; ?>
@foreach (App\Models\Ucode::MyUcodes() as $ucode)
  {{ $varName }}['ucodes-{{ $ucode->id }}'] = {
    id: {{ $ucode->id }},
    name: '{{ $ucode->ucode }}',
    @if ( count($ucodes) > 0 )
        media: [
            @foreach ($ucode->media as $medium)
                { id: {{ $medium->id }}, title: '{{ sanitizeToJS($medium->title) }}' },
            @endforeach
        ]
    @endif
  };
@endforeach
