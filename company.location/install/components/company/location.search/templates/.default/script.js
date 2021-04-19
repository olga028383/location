'use strict';

document.addEventListener("DOMContentLoaded", function () {
  const link = document.querySelector('.js-city');
  const popup = document.querySelector('.popup');
  const closePopup = popup.querySelector('.popup__button');
  const searchForm = popup.querySelector('.popup__form');
  const searchField = searchForm.querySelector('input[name="city"]');
  const results = popup.querySelector('.popup__results');

  const removeChild = () => {
    while (results.firstChild) {
      results.firstChild.remove();
    }
  };

  const submitForm = () => {
    getData(
      new FormData(searchForm),
      (data) => {
        console.log(data);
      }
    );
  };

  const createItems = (data) => {
    const elements = document.createDocumentFragment();

    data.items.forEach((item) => {

      let element = document.createElement('div');
      element.classList.add('popup__result');
      element.textContent = item['UF_CITY_NAME'];
      element.dataset.id = item['ID'];

      element.addEventListener('click', () => {
        removeChild();
        searchField.value = item['UF_CITY_NAME'];

        submitForm();
        window.location.reload();
      });

      elements.appendChild(element);
    });
    results.appendChild(elements);
  };


  link.addEventListener('click', (evt) => {
    evt.preventDefault();
    popup.classList.remove('popup--hidden');
  });

  closePopup.addEventListener('click', () => {
    popup.classList.add('popup--hidden');
  });

  searchField.addEventListener('input', (evt) => {
    const data = new FormData();
    data.append('AJAX', 'Y');
    data.append('action', 'search');
    data.append('city', evt.target.value);

    getData(
      data,
      (data) => {
        removeChild();
        if (data) {
          createItems(data);
        }
      },
    );

  });
});


function debounce(f, ms) {
  let isCooldown = false;
  return function () {
    if (isCooldown) return;

    f.apply(this, arguments);

    isCooldown = true;

    setTimeout(() => isCooldown = false, ms);
  };
}

function checkStatus(response) {
  if (response.ok) {
    return response;
  }

  const {statusText, status} = response;
  throw new Error(`${status} — ${statusText}`);
}

function getData(data, loadHandler) {
  fetch('', {
    method: 'POST',
    body: data,
  })
    .then(checkStatus)
    .then((response) => {
      return response.json()
    })
    .then((data) => {
      loadHandler(data)
    })
    .catch((error) => {
      //Здесь обработка ощибки
    });
}