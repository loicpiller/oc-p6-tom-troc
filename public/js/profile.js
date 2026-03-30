document.getElementById('avatar-input').addEventListener('change', function () {
    if (this.files.length > 0) {
        document.getElementById('avatar-form').submit();
    }
});
