$('.formData').submit(function(e){
    e.preventDefault();
    let data = $(this).serialize();

    $.post('http://localhost:3000/posts/modal', data, function(d){
        console.log(d);
    }, 'json');
});