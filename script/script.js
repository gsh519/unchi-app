$('document').ready(function() {
  $('.comment-form').on('focus', function() {
    $(this).parent().find('label').addClass('active');
  })

  $('#comment').on('focusout', function() {
    if (!this.value) {
      $(this).parent().find('label').removeClass('active');
    }
  });

  $('.login-input').on('focus', function () {
    $(this).parent().find('label').addClass('active');
  })

  $('.login-input').on('focusout', function() {
    if (!this.value) {
      $(this).parent().find('label').removeClass('active');
    }
  });

  $('.item').on('click', function () {
    let index = $('.item').index(this);
    $('.item').removeClass('select');
    $(this).addClass('select');
    $('.amount-input').attr('value', index);
  })

})
