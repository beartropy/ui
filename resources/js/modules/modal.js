// Modal Module
export function openModal(id) {
    id = id.toLowerCase();
    window.dispatchEvent(new CustomEvent(`open-modal-${id}`));
}

export function closeModal(id) {
    id = id.toLowerCase();
    window.dispatchEvent(new CustomEvent(`close-modal-${id}`));
}
