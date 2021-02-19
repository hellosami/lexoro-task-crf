let lf = document.getElementById('login-form');
let rf = document.getElementById('registration-form');

let lt = document.getElementById('lt');
let rt = document.getElementById('rt');

lt.addEventListener('click', function() {
    lt.classList.add("btn-active");
    rt.classList.remove("btn-active");
    rf.style.display = 'none';
    lf.style.display = 'block';
})

rt.addEventListener('click', function() {
    lf.style.display = 'none';
    rf.style.display = 'block';
    rt.classList.add("btn-active");
    lt.classList.remove("btn-active");
})


lf.onsubmit = function(e) { return validateForm(this); };
rf.onsubmit = function(e) { return validateForm(this); };


function validateForm(obj) {
    let username = document.getElementById(obj[0].id).value;
    let password = document.getElementById(obj[1].id).value;

    if(!/\s/.test(username)) {
        if(/^(?=.*[A-Za-z0-9])(?=.*[@$!%*#?&])[A-Za-z0-9\d@$!%*#?&]{6,}$/.test(password)) {
            return true;
        } else {
            alert("THE PASSWORD HAS TO BE AT LEAST 6 CHARACTERS LONG AND CONTAIN 1 SYMBOL!");
            return false;
        }
    } else {
        alert("THE USERNAME SHOULD NOT CONTAIN SPACES!");
        return false;
    }
}
