document.getElementById('custom-button').addEventListener('click', function() {
    document.getElementById('real-file').click();
});

document.getElementById('real-file').addEventListener('change', function() {
    document.getElementById('custom-text').textContent = document.getElementById('real-file').files[0].name;
});