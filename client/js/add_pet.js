function populateEditModal(pet) {
    document.getElementById('edit-pet-id').value = pet.id;
    document.getElementById('edit-pet-age').value = pet.age;
    document.getElementById('edit-pet-color').value = pet.color;
    document.getElementById('edit-pet-weight').value = pet.weight;
}
