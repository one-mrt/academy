
var error = [];
var pas = [];
var stack = {
    error: {
        password: {
            actual: "Пароль должен состоять из букв латинского алфавита специальных символов и цифр и не меньше 6 символов",
            confirm: "Пароли не совпадают",
            status: 0,
            num: false,
            spec: false,
            sym: false,
            SYM: false,
            copy: false,
        },
    },
    password: [],
    valid: {
        password: {
            el: {
                pas: null,
                confirm: null
            },
            base: {
                SYM: "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
                sym: "abcdefghijkemnopqrstuvwxyz",
                spec: "~!@#$%^&*()_+`-={}[]:;<>./\"",
                num: "0123456789",
                fullsym: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkemnopqrstuvwxyz",
            },
            status: {
                num: false,
                sym: false,
                SYM: false,
                spec: false,
                copy: false,
                len: false,
                fullsym: false,
            },
            error: {
                fullsym: "должен быть хотя бы один символ латинского алфавита",
                num: "должена быть хотя бы одна цифра",
                sym: "должен быть хотя бы один маленький символ",
                SYM: "должен быть хотя бы один большой символ",
                spec: "должен быть хотя бы один специальный символ",
                copy: "Пароли не совпадают",
                length: "Пароль должен быть не менее 6 символов и не более 16",
            },
        },
        phone: {
            el: null,
            base: null
        },
        email: {
            base: {
                SYM: "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
                sym: "abcdefghijkemnopqrstuvwxyz",
                spec: "!#$%&'*+-/=?^_`{|}~",
                num: "0123456789",
            }
        },
        form: {
            login: {
                status: false,
                el: null,
            },
            email: {
                status: false,
                el: null,
            },
            name: {
                status: false,
                el: null,            
            },
            password: {
                status: false,
                el: null,
            },
            confirmpassword: {
                status: false,
                el: null,
            },
            phone: {
                status: false,
                el: null,
            }
        }
    }
}

function value(el) {
    var v = el.value,
        type = el.type;
    var a = "ABCDEFGHIKLMNOPQRSTUYWXVZabcdefghiklmnopqrstuywxvz1234567890",
        i = 0;
    switch(type) {
        case 'tel':
            var valid = el.getAttribute('data-valid');
            if(v.length == Number(valid))
                return true;
            else return false;
        case 'password':
            break;
        case 'login':
            break;
    }

    for(var k = 0; k < a.length; k++)
        for(var j = 0; j < v.length; j++)
            if(v[j] == a[k]) i++;
    if(i == v.length) return true;
    else return false;
}

function phone(el) {
    var v = el.value,
        s = '0123456789',
        max = el.getAttribute('data-valid'),
        i = 0,
        value = '';
    for(var k = 0; k < s.length; k++) {
        for(var j = 0; j < v.length; j++) {
            if(v[j] == s[k]) {
                i++;
                value += v[j];
            } else v[j] = '';
        }
    }
    if(i == value.length && value.length == Number(max)) {
        stack.valid.form.phone.status = true;
        validToogle(true, el);
    } else {
        stack.valid.form.phone.status = false;
        validToogle(false, el);
    }
    stack.valid.form.phone.el = el;
}

function login(el) {
    var value = el.value;
    var sym = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkemnopqrstuvwxyz";
    var num = "0123456789";//-_.";
    var list = sym + num;
    var mes = {
        default: el.nextElementSibling.getAttribute('data-error'),
        one: "Первый символ не может быть цифрой или спец. символом",
        sym: "Логин содержит запрещеные символы",
        min: "Логин должен быть больше или равен 6 символам",
        max: "Логин должен быть меньше или равен 12 символам",
    };
    var i = 0,
        one = 0;

    for(var j = 0; j < sym.length; j++) if(value[0] == sym[j]) one++;

    for(var k = 0; k < list.length; k++)
        for(var j = 0; j < value.length; j++)
            if(list[k] == value[j]) i++;

    var elError = el.nextElementSibling;

    /*if(one == 0) {
        elError.setAttribute('data-error', mes.one);
    } else if(i != value.length) {
        elError.setAttribute("data-error", mes.sym);
    } else */if(value.length < 6) { 
        elError.setAttribute('data-error', mes.min);
    } else if(value.length > 12) { 
        elError.setAttribute('data-error', mes.max);
    } else {
        elError.setAttribute('data-error', mes.default);
    }

    if(/*one > 0 && i == value.length && */value.length > 6 && value.length <= 12) {
        stack.valid.form.login.status = true;
        validToogle(true, el);
    } else {
        stack.valid.form.login.status = false;
        validToogle(false, el);
    }
    stack.valid.form.login.el = el;
        
}

function validName(el) {
    if(el != null && el != undefined) {
        var rus = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя',
            value = el.value,
            ind = 0;
        for(var k = 0; k < value.length; k++)
            for(var j = 0; j < rus.length; j++)
                if(value[k] == rus[j]) ind++;
        if(value.length < 3 || value.length > 12) {
            stack.valid.form.name.status = false;
            return false;
        }
        if(value.length == ind) {
            stack.valid.form.name.status = true;
            return true;
        }
        stack.valid.form.name.el = el;
    }
    return false;
}

function password(el) {
    var value = el.value,
        pasNum = el.getAttribute('data-valid'),
        SYM = "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
        sym = "abcdefghijkemnopqrstuvwxyz",
        fullSym = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkemnopqrstuvwxyz",
        spec = "~!@#$%^&*()_+`-={}[]:;<>./\"",
        num = "0123456789";
    var sspec = stack.valid.password.status.spec,
        sSYM = stack.valid.password.status.SYM,
        ssym = stack.valid.password.status.sym,
        snum = stack.valid.password.status.num,
        slen = stack.valid.password.status.len;

    if(pasNum != null && pasNum != undefined) {
        switch(pasNum) {
            case '0':
                stack.password[pasNum] = el;
                /*if(!isValidSpec(el, spec)) {
                    stack.error.password.actual = "должен быть хотя бы один специальный символ [ " + spec + " ]";
                    stack.error.password.status += 1;
                    sspec = false;
                    el.nextElementSibling.setAttribute('data-error', stack.error.password.actual);
                } else {
                    if(stack.error.password.status != 0)
                        stack.error.password.status -= 1;
                    sspec = true;
                }*/
                /*if(!isValidSpec(el, SYM)) {
                    stack.error.password.actual = "должен быть хотя бы один большой символ [ " + SYM + " ]";
                    stack.error.password.status += 1;
                    sSYM = false;
                    el.nextElementSibling.setAttribute('data-error', stack.error.password.actual);
                } else {
                    if(stack.error.password.status != 0)
                        stack.error.password.status -= 1;
                    sSYM = true;
                }*/
                /*if(!isValidSpec(el, sym)) {
                    stack.error.password.actual = "должен быть хотя бы один маленький символ [ " + sym + " ]";
                    stack.error.password.status += 1;
                    ssym = false;
                    el.nextElementSibling.setAttribute('data-error', stack.error.password.actual);
                } else {
                    if(stack.error.password.status != 0)
                        stack.error.password.status -= 1;
                    ssym = true;
                }*/
                if(!isValidSpec(el, fullSym)) {
                    stack.error.password.actual = "должен быть хотя бы один символ латинского алфавита [ " + sym + " ]";
                    stack.error.password.status += 1;
                    fullsym = false;
                    el.nextElementSibling.setAttribute('data-error', stack.error.password.actual);
                } else {
                    if(stack.error.password.status != 0)
                        stack.error.password.status -= 1;
                    fullsym = true;
                }
                if(!isValidSpec(el, num)) {
                    stack.error.password.actual = "должена быть хотя бы одна цифра [ " + num + " ]";
                    stack.error.password.status += 1;
                    snum = false;
                    el.nextElementSibling.setAttribute('data-error', stack.error.password.actual);
                } else {
                    if(stack.error.password.status != 0)
                        stack.error.password.status -= 1;
                    snum = true;
                }
                if(el.value.length < 6 || el.value.length > 16) {
                    stack.error.password.actual = "Пароль должен быть не менее 6 символов и не более 16";
                    stack.error.password.status += 1;
                    slen = false;
                    el.nextElementSibling.setAttribute('data-error', stack.error.password.actual);
                } else {
                    if(stack.error.password.status != 0)
                        stack.error.password.status -= 1;
                    slen = true;
                }
                isValidPassword(el);

                if(/*sspec && sSYM && ssym && */snum && slen && fullSym) {
                    stack.valid.form.password.status = true;
                    validToogle(true, el);
                }
                else {
                    stack.valid.form.password.status = false;
                    validToogle(false, el);
                }
                break;
            case '1':
                stack.password[pasNum] = el;
                isValidPassword(el);
                break;
        }
    }

    var mes = {
        bigsym: "должен быть хотя бы один большой символ",
        specsym: "должен быть хотя бы один специальный символ [ " + spec + " ]",
        small: "должен быть хотя бы один маленький символ",
    }

    stack.valid.form.password.el = el;
}

function isValidSpec(el, sym) {
    if(el != null && el != undefined)
        for(var k = 0; k < el.value.length; k++)
            for(var j = 0; j < sym.length; j++)
                if(sym[j] == el.value[k]) return true;
    return false;
}



function isValidPassword(el) {
    if(el != null && el != undefined) {
        var pas_1 = stack.password[0],
            pas_2 = stack.password[1];

        if(pas_1.value.length != 0 && pas_2.value.length != 0) {
            if(pas_1.value != pas_2.value) {
                pas_2.classList.remove('valid');
                pas_2.classList.add('invalid');
                stack.valid.form.confirmpassword.status = false;
            } else {
                pas_2.classList.remove('invalid');
                pas_2.classList.add('valid');
                stack.valid.form.confirmpassword.status = true;
            }
        }

        stack.valid.form.confirmpassword.el = el;
    }
}

function validToogle(e, el) {
    if(e) {
        el.classList.remove('invalid');
        el.classList.add('valid');
    } else  {
        el.classList.remove('valid');
        el.classList.add('invalid');
    }
}

function email(el) {
    if(el != null && el != undefined) {
        var sym = "!#$%&'*+-/=?^_`{|}~",
            sobaka = false,
            symS = '',
            tochka = false,
            symT = '',
            value = el.value;
        for(var k = 0; k < value.length; k++) {
            if(value[k] == "@") {
                sobaka = true;
                tochka = false;
            } else if(value[k] == '.') {
                sobaka = false;
                tochka = true;
            } else {
                if(sobaka && !tochka) {
                    symS += value[k];
                } else if(tochka && !sobaka) {
                    symT += value[k]
                }
            }

        }
        if(symS != '' && symT != '' && symS != '_' && symT != '_') {
            stack.valid.form.email.status = true;
            validToogle(true, el);
        } else {
            stack.valid.form.email.status = false;
            validToogle(false, el);
        }
        stack.valid.form.email.el = el;
    } else return false;
}

function isValid() {
    var sym = '';
}


    var valid = document.body.querySelectorAll('[data-valid]');
    for(var k = 0; k < valid.length; k++) {
        var el = valid[k];
        if(el.tagName == 'FORM') {
            el.addEventListener('submit', function(e) {
                var email = stack.valid.form.email.status,
                    login = stack.valid.form.login.status,
                    name = stack.valid.form.name.status,
                    pas = stack.valid.form.password.status,
                    pasconf = stack.valid.form.confirmpassword.status,
                    phone = stack.valid.form.phone.status;

                if(email && login && name && pas && pasconf && phone) {

                } else {
                    e.preventDefault();

                    if(!email) stack.valid.form.email.el.focus();
                    if(!login) stack.valid.form.login.el.focus();
                    if(!name) stack.valid.form.name.el.focus();
                    if(!pas) stack.valid.form.password.el.focus();
                    if(!pasconf) stack.valid.form.confirmpassword.el.focus();
                    if(!phone) stack.valid.form.phone.el.focus();
                }
            })
        } else {
            switch(el.type) {
                case 'tel':
                    el.setAttribute('data-inputmask', "'mask': '+7 (999) 999-99-99'");
                    $(el).inputmask();
                    $(el).on('input', function(e) {
                        phone(e.target);
                    })
                    break;
                case 'text':
                    if(el.hasAttribute('data-valid')) {
                        switch(el.getAttribute('data-valid')) {
                            case "email":
                                $(el).on('input', function(e) {
                                    email(e.target)
                                });
                                break;
                            case 'login':
                                $(el).on('input', function(e) {
                                    login(e.target)
                                })
                                break;
                            case 'name':
                                $(el).on('input', function(e) {
                                    validToogle(validName(e.target), e.target);
                                })
                                break;
                        }
                    }
                    break;
                case 'password':
                    if(el.hasAttribute('data-valid')) {
                        stack.password[el.getAttribute('data-valid')] = el;
                        el.addEventListener('input', function(e) {
                            password(e.target)
                        })
                    }
                    break;
                case 'submit':
                    el.addEventListener('submit', function(e) {

                    })
                    break;
            }

            if(el.value != null || el.value != undefined || el.value != '') {
                if(el.value.length > 0) el.classList.add('active');
                else el.classList.remove('active');
                if(el.hasAttribute('placeholder')) el.classList.add('active')
            }

            $(el).on('input', function(e) {
                var target = e.target,
                    valueLength = target.value;
                if(valueLength.length > 0) target.classList.add('active');
                else target.classList.remove('active')
                if(target.hasAttribute('placeholder')) target.classList.add('active')
            })
        }
    }