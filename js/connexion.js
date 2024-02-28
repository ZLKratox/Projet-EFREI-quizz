document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('login-form');

    form.onsubmit = function (e) {
        e.preventDefault();

        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost/monquiz/backend/connexion.php', true);
        xhr.onload = function () {
            if (this.status == 200) {
                var response = JSON.parse(this.response);
                if (response.success) {
                    window.location.href = 'dashboard.html';
                } else {
                    alert(response.error);
                }
            } else {
                alert("Erreur lors de la connexion.");
            }
        };
        xhr.send(formData);
    };
});
