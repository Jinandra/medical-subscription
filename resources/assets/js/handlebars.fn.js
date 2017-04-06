(function() {
  function checkCondition(v1, operator, v2) {
    switch(operator) {
      case '==':
        return (v1 == v2);
      case '===':
        return (v1 === v2);
      case '!==':
        return (v1 !== v2);
      case '<':
        return (v1 < v2);
      case '<=':
        return (v1 <= v2);
      case '>':
        return (v1 > v2);
      case '>=':
        return (v1 >= v2);
      case '&&':
        return (v1 && v2);
      case '||':
        return (v1 || v2);
      default:
        return false;
    }
  }

  Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {
    var modifier = typeof options.hash.modifier === 'undefined' ? 0 : options.hash.modifier;
    return checkCondition(v1, operator, v2+modifier)
      ? options.fn(this)
      : options.inverse(this);
  });

  // To marks the folder as self delete
  Handlebars.registerHelper('ifSelfMedia', function (obj, options) {
    return obj === 'self' ? options.fn(this) : options.inverse(this);
  });

  Handlebars.registerHelper('defVal', function (value, defaultValue) {
    return new Handlebars.SafeString(value || defaultValue);
  });
}());
