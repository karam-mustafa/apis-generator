$(document).ready(() => {
    let column_arr = [];

    function fellSelectOptions() {
        $('.column_to_validate').empty();
        column_arr.forEach((item) => {
            $('.column_to_validate').append(`<option>${item}</option>`);
        })
    }

    $('.add_new_column').on('click', function () {
        let column_name = $('.column_name').val();
        let column_type = $('.column_type').val();
        let tr = `
                    <div class="card api_card my-1 mx-1">
                        <div class="card-body center_elements_space_between p-0">
                            <h6 class="card-title">column: &nbsp; <b>${column_name}</b></h6>
                            <input type="hidden" value="${column_name}" class="column_name" name="column[${column_name}][type][${column_type}]">
                            <h6 class="card-subtitle mb-2">Type: &nbsp; <b>${column_type}</b></h6>
                            <div class="form-group">
                                <div class=" text-secondary process_icon delete_api_card">X</div>
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
        `;
        if (column_name === '') return $('.error_message').text('name can not be empty');
        if (column_arr.includes(column_name)) return $('.error_message').text('this column has been added before');
        $('.error_message').text('');
        column_arr.push(column_name);
        $('.api_details_area').append(tr);
        fellSelectOptions();
    });
    $(document).on('click', '.add_new_validation_rule', function () {
        let value = $(this).closest('.api_card').find('.validation_rule_value').val();
        let column_name = $(this).closest('.api_card').find('.column_name').val();
        let validation_rule_item = `
        <div class="validation_item_area m-2">
            <input type="text" readonly name="column[${column_name}][validation][${value}]" value="${value}">
            <div class=" text-secondary process_icon delete_validation_item">X</div>
        </div>
        `;
        $(this).closest('.api_card').find('.validation_area').append(validation_rule_item);
    });
    $(document).on('click', '.delete_validation_row', function () {
        $(this).closest('.api_card').remove();
    });
    $(document).on('click', '.delete_validation_item', function () {
        $(this).parent().remove();
    });
    $(document).on('click', '.delete_api_card', function () {
        let column_name = $(this).closest('.api_card').find(".column_name").val();
        column_arr = column_arr.filter(item => item !== column_name);
        fellSelectOptions();
        $(this).closest('.api_card').remove();
    });

});