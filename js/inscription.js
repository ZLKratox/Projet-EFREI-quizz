document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('signup-form');

    form.onsubmit = function (e) {
        e.preventDefault();

        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost/monquiz/backend/inscription.php', true);
        xhr.onload = function () {
            var response = JSON.parse(this.response);
            if (response.success) {
                window.location.href = 'login.html';
            } else {
                alert(response.error);
            }
        };
        xhr.send(formData);
    };
});
