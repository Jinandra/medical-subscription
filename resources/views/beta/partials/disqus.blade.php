{{--
  -- PARAMS:
  -- $identified => discussion identifier
  --}}

<div id="disqus_thread"></div>
<script>
  var reset = function (newIdentifier, newUrl, newTitle, newLanguage) {
    DISQUS.reset({
      reload: true,
      config: function () {
        this.page.identifier = "{{ $identifier }}";
        this.page.url = "{{ url($identifier) }}";
      }
    });
  };
  (function () {  // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = '//enfolink.disqus.com/embed.js';
    s.setAttribute('data-timestamp', + new Date());
    (d.head || d.body).appendChild(s);
  })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
