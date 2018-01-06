export default {
  doRequest(method, args = {}) {
    return new Promise((resolve, reject) => {
      const ajaxOptions = {
        url: '/api/' + method,
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
