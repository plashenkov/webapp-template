// import format from 'date-fns/format';
// import store from '../store/store';

function objectToFormData(obj) {
  if (obj instanceof FormData) {
    return obj;
  }

  const formData = new FormData;

  function processItem(item) {
    // if (item instanceof Date) return format(item);
    if (item === true) return 1;
    if (item === false) return 0;
    return item;
  }

  function o2fd(obj, prevKey) {
    Object.keys(obj).forEach(key => {
      const item = obj[key];

      key = prevKey ? (prevKey + '[' + key + ']') : key;

      if (item instanceof Object && item.constructor === Object || Array.isArray(item)) {
        o2fd(item, key);
      } else if (item !== null && item !== undefined) {
        formData.append(key, processItem(item));
      }
    });
  }

  o2fd(obj);

  return formData;
}

export default {
  doRequest(method, data = {}, appendToken = true) {
    /*
    if (appendToken && store.state.token) {
      data.token = store.state.token;
    }
    */

    const url = '/api/' + method.replace(/^\//, '');

    const options = {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: objectToFormData(data)
    };

    return fetch(url, options)
      .then(response => response.text())
      .then(text => text ? JSON.parse(text) : {})
      .then(response => {
        if (response.error) {
          throw response.error;
        }

        return response;
      });
  }
}
