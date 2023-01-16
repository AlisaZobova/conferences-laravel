import './bootstrap';

import 'intl-tel-input/build/css/intlTelInput.css';
import 'intl-tel-input/build/js/intlTelInput';
import 'intl-tel-input/build/js/utils';

import '../css/register.css'

import '../css/app.css'

import Alpine from 'alpinejs';

import initMap from './map.js';

import intlTelInput from 'intl-tel-input';

const input = document.querySelector("#intl-phone");
const phone = document.querySelector("#phone");
const message = document.querySelector("#invalid-msg");

if (input) {
    let iti = intlTelInput(input, {
        initialCountry: "UA",
        preferredCountries: ["UA", "US"]
    });
    input.addEventListener('input', function () {

        if (!iti.isValidNumber()){
            message.type = null;
            phone.value = null;
        }
        else {
            message.type = "hidden";
            phone.value = iti.getNumber();
        }
    });
}

initMap();

window.Alpine = Alpine;

Alpine.start();
