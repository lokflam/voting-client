$(function() {
    $('#candidates').on('click', '.add-candidate', function(e) {
        e.preventDefault();
        var last_field = $('.candidate-field').last();
        var candidate_field = last_field.clone();
        last_field.find('.add-candidate')
            .removeClass('add-candidate').addClass('remove-candidate')
            .removeClass('btn-info').addClass('btn-danger')
            .text('Remove');
        candidate_field.find('input[type=text], textarea').val('');
        candidate_field.find('.is-invalid').removeClass('is-invalid');
        $('#candidates').append(candidate_field);
    });

    $('#candidates').on('click', '.remove-candidate', function(e) {
        e.preventDefault();
        $(this).parents('.candidate-field').remove();
    });

    $('input:radio[name="code_mode"]').change(function(e) {
        e.preventDefault();
        if($(this).is(':checked') && $(this).val() == 'generate') {
            $('#quantity-field').removeClass('d-none');
        } else {
            $('#quantity-field').addClass('d-none');
        }

        if($(this).is(':checked') && $(this).val() == 'custom') {
            $('#codes-field').removeClass('d-none');
        } else {
            $('#codes-field').addClass('d-none');
        }
    });

    $('#btn-redirect').click(function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        location.href = url+'/'+$('input[name="query"]').val();
        return false;
     });

     $('#search-vote-admin').click(function(e) {
        e.preventDefault();
        location.href = base_url+'/admin/vote/'+$('input[name="id"]').val()+'/update';
        return false;
     });

     $('body').on('click', '.count-ballot', function(e) {
        e.preventDefault();
        var vote_id = $(this).data('id');
        var private_key = prompt('Please enter your private key');
        $.post(base_url+'/ballot/count', {private_key: private_key, vote_id, vote_id}, function(result) {
           if(result.success) {
              location.href = base_url+'/batch/'+result.id;
           } else {
              alert(result.message);
           }
        });
     });
});