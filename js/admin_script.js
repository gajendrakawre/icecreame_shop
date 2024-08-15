const userBTN = document.querySelector('#user-btn');
userBTN.addEventListener('click', function(){
    const userBox = document.querySelector('.profile-detail');
    userBox.classList.toggle('active');
})

const toggleBTN = document.querySelector('.toggle-btn');
toggleBTN.addEventListener('click', function(){
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
})
