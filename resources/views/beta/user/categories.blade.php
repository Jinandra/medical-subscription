{{--
  -- PARAMS:
  -- $categories => array of category to display
  --}}
<?php $selecteds = array_keys(is_null(old('categories')) ? [] : old('categories')); ?>
<table>
  <tbody>
  @foreach ($categories as $category)
    <tr>
      <td width="10"><label class="checkbox-default mr10"><input id="category-{{ $category->id }}" type="checkbox" name="categories[{{ $category->id }}]" {{ in_array($category->id, $selecteds) ? 'checked="checked"' : '' }}><span class="ico-checkbox"></span></label></td>
      <td><label for="category-{{ $category->id }}">{{ $category->name }}</label></td>
    </tr>
  @endforeach
  </tbody>
</table>
