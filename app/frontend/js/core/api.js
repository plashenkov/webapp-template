function objectToFormData(obj, onlyIfHasFiles = false) {
  if (obj instanceof FormData) {
    return obj;
  }

  const formData = new FormData;
  let hasFiles = false;

  function o2fd(obj, prevKey) {
    Object.keys(obj).forEach(key => {
      const item = obj[key];
      const isFile = item instanceof File;

      if (isFile) {
        hasFiles = true;
      }

      key = prevKey ? (prevKey + '[' + key + ']') : key;

      if (item === Object(item) && !isFile) {
        o2fd(item, key);
      } else {
        formData.append(key, item);
      }
    });
  }

  o2fd(obj);

  return !onlyIfHasFiles || hasFiles ? formData : obj;
}

export default {
  getUrl(method, args = {}) {
    const url = '/api/' + method.replace(/^\//, '');

    args = jQuery.param(args);

    return args ? (url + '?' + args) : url;
  },

  /*
  getUrlWithToken(method, args = {}) {
    const token = store.state.token;
    if (token) {
      args.token = token;
    }

    return this.getUrl(method, params);
  },
  */

  doRequest(method, args = {}) {
    return new Promise((resolve, reject) => {
      args = objectToFormData(args, true);

      /*
      const token = store.state.token;
      if (token) {
        if (args instanceof FormData) {
          args.set('token', token);
        } else {
          args.token = token;
        }
      }
      */

      const ajaxOptions = {
        url: this.getUrl(method),
        method: 'POST',
        cache: false
      };

      ajaxOptions.data = args;
      if (args instanceof FormData) {
        ajaxOptions.contentType = false;
        ajaxOptions.processData = false;
      }

      jQuery
        .ajax(ajaxOptions)
        .done(result => {
          resolve(result);
        })
        .fail(xhr => {
          if (!xhr.status) {
            console.log('API: AJAX connection error');
            reject();
          } else if (xhr.responseJSON) {
            reject(xhr.responseJSON.error);
          } else {
            reject(xhr.responseText);
          }
        });
    });
  }
}
