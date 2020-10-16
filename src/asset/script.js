$(document).ready(() => {
    /** @type {array} */
    let column_arr = [];
    function fellSelectOptions() {
        $('.column_to_validate').empty();
        column_arr.forEach((item) => {
            $('.column_to_validate').append(`<option>${item}</option>`);
        });
    }
    /**
     * @description this event to check from inputs value
     *
     */
    $('.api_title').on('input', function () {
        $(this).validator(".api_title_error_message", 'title', $(this).val());
    });
    $('.add_new_column').on('click', function () {
        /** @type {string} */
        let column_name = $('.column_name').val();
        /** @type {string} */
        let column_type = $('.column_type').val();
        /** @type {object} */
        let tr = `
                    <div class="col-md-4 mb-4">
                    <div class="card api_card my-1 mx-1">
                        <div class="card-body center_elements_space_between p-0">
                            <h6 class="card-title">column: &nbsp; <b>${column_name}</b></h6>
                            <input type="hidden" value="${column_name}" class="column_name" name="column[${column_name}][type][${column_type}]">
                            <h6 class="card-subtitle mb-2">Type: &nbsp; <b>${column_type}</b></h6>
                            <div class="form-group">
                                <div class=" text-secondary process_icon delete_api_card_sm ">X</div>
                             </div>
                        </div>
                         <div class="center_elements_space_between w-100">
                                    <div class="form-group">
                                        <h6 class="card-title mt-3 pr-2">Validation </h6>
                                    </div>
                                    <div class="form-group pr-2">
                                        <input type="text" class="form-control validation_rule_value" placeholder="Type rule">
                                    </div>
                                    <div class="form-group">
                                        <div class="add_new_validation_rule hvr-shutter-in-vertical process_icon">+</div>
                                    </div>
                         </div>
                        <hr>
                         <div class="validation_area">
                        </div>
                    </div>
</div>
        `;
        let validatorCheck = $(this).validator(".column_error_message", 'column', column_name, column_arr);
        // if validation pass then append new card
        if (validatorCheck === true) {
            column_arr.push(column_name);
            $('.api_details_area').append(tr);
            fellSelectOptions();
        }
    });
    /**
     * @description this event to add new validation rule item
     * @return void
     */
    $(document).on('click', '.add_new_validation_rule', function () {
        /** @type {string} */
        let value = $(this).closest('.api_card').find('.validation_rule_value').val();
        /** @type {string} */
        let column_name = $(this).closest('.api_card').find('.column_name').val();
        /** @type {object} */
        let validation_rule_item = `
        <div class="validation_item_area m-2">
            <input class="validation_item_input" type="text" readonly name="column[${column_name}][validation][${value}]" value="${value}">
            <div class=" text-secondary process_icon delete_validation_item_small">X</div>
        </div>
        `;
        $(this).closest('.api_card').find('.validation_area').append(validation_rule_item);
    });
    /**
     * @description delete validation row
     * @return void
     */
    $(document).on('click', '.delete_validation_row', function () {
        $(this).closest('.api_card').remove();
    });
    /**
     * @description delete validation rule
     * @return void
     */
    $(document).on('click', '.delete_validation_item_small', function () {
        $(this).parent().remove();
    });
    /**
     * @description delete column card
     * @return void
     */
    $(document).on('click', '.delete_api_card', function () {
        let column_name = $(this).closest('.col-md-4').find(".column_name").val();
        column_arr = column_arr.filter(item => item !== column_name);
        fellSelectOptions();
        $(this).closest('.col-md-4').remove();
    });
});

/**
 * @description this is global function to validate custom type of inputs
 * @return boolean
 */
$.fn.validator = function (error_message_place, item, value, arr = []) {
    /** @type {object} */
    let error_message_item = $(`${error_message_place}`);
    // check if this value from inputs is empty
    if (value === '') return error_message_item.text(`${item} can not be empty`);
    // if there any array to determine if this value within values of array
    if (arr.length > 0) {
        if (arr.includes(value)) return error_message_item.text(`this ${item} has been added before`);
    }
    // if validate passes empty message [p]
    error_message_item.text('');
    return true;
};
///////////////////////////////////////////////////
//////////start  particles.js config  /////////////
particlesJS("background", {
    "particles": {
        "number": {"value": 260, "density": {"enable": true, "value_area": 800}},
        "color": {"value": "#000000"},
        "shape": {
            "type": "circle",
            "stroke": {"width": 0, "color": "#000000"},
            "polygon": {"nb_sides": 5},
            "image": {"src": "img/github.svg", "width": 100, "height": 100}
        },
        "opacity": {"value": 1, "random": true, "anim": {"enable": true, "speed": 1, "opacity_min": 0, "sync": false}},
        "size": {"value": 3, "random": true, "anim": {"enable": false, "speed": 4, "size_min": 0.3, "sync": false}},
        "line_linked": {"enable": false, "distance": 150, "color": "#ffffff", "opacity": 0.4, "width": 1},
        "move": {
            "enable": true,
            "speed": 1,
            "direction": "none",
            "random": true,
            "straight": false,
            "out_mode": "out",
            "bounce": false,
            "attract": {"enable": false, "rotateX": 600, "rotateY": 600}
        }
    },
    "interactivity": {
        "detect_on": "canvas",
        "events": {
            "onhover": {"enable": true, "mode": "bubble"},
            "onclick": {"enable": true, "mode": "repulse"},
            "resize": true
        },
        "modes": {
            "grab": {"distance": 400, "line_linked": {"opacity": 1}},
            "bubble": {"distance": 250, "size": 0, "duration": 2, "opacity": 0, "speed": 3},
            "repulse": {"distance": 400, "duration": 0.4},
            "push": {"particles_nb": 4},
            "remove": {"particles_nb": 2}
        }
    },
    "retina_detect": true
});
//////////end  particles.js config  /////////////
//////////////////////////////////////////////////////
//////////start dark mode script config /////////////
function addDarkmodeWidget() {
    new Darkmode().showWidget();
}
window.addEventListener('load', addDarkmodeWidget);
const options = {
    bottom: '64px', // default: '32px'
    right: 'unset', // default: '32px'
    left: '32px', // default: 'unset'
    time: '0.5s', // default: '0.3s'
    mixColor: '#fff', // default: '#fff'
    backgroundColor: '#fff',  // default: '#fff'
    buttonColorDark: '#100f2c',  // default: '#100f2c'
    buttonColorLight: '#fff', // default: '#fff'
    saveInCookies: false, // default: true,
    label: '🌓', // default: ''
    autoMatchOsTheme: true // default: true
};

const darkmode = new Darkmode(options);
darkmode.showWidget();
/////////// end dark mode script config /////////////
