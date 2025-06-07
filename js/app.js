document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.filter-form');
    if (!form) return;

    const inputs = form.querySelectorAll(
    'input[name="search"], select[name="category_id"], select[name="sort"]'
    );

    inputs.forEach(el => {
    const eventName = el.tagName.toLowerCase() === 'input' ? 'input' : 'change';
    el.addEventListener(eventName, () => form.submit());
    });
});
document.getElementById('deleteForm').addEventListener('submit', function(e) {
    if (!confirm('Это действие нельзя отменить! Вы уверены?')) {
        e.preventDefault();
    }
});
