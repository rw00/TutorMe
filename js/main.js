/* jshint browser: true */
/* global $: false */
/* global jquery: false */
/* global BootstrapDialog: false */

/* jslint shadow: true */

/*
    shadow enabled. Example: for (var i = 0; ...; ...) { ...; }        for (var i = 0; ...; ...) { }
    // 'i' is already defined

    browser enabled. Example: 'document' is not defined
*/

var TEXT_REGEX = /^[a-z]+$/i;
var EMAIL_REGEX = /^([\w\-]+(?:\.[\w\-]+)*)@((?:[\w\-]+\.)*\w[\w\-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

var MIN_PASSWORD_LENGTH = 8;

function pageLoad() {
    var showMoreContent = document.getElementsByClassName("show-more-content");
    if (showMoreContent !== null) {
        for (var i = 0; i < showMoreContent.length; i++) {
            showMoreContent[i].onclick = toggleContent;
        }
    }

    var profileForm = document.getElementById("profile-form");
    if (profileForm !== null) {
        profileForm.onsubmit = profileFormValidation;
    }

    var searchInput = document.getElementById("search-input");
    if (searchInput !== null) {
        searchInput.onkeyup = search;
    }

    var contactForm = document.getElementById("contact-form");
    if (contactForm !== null) {
        contactForm.onsubmit = contactFormValidation;
    }

    var forgotPassword = document.getElementById("forgot-password");
    if (forgotPassword !== null) {
        forgotPassword.onclick = resetPassword;
    }

    var fileInputs = document.querySelectorAll(".btn-file input[type='file']");
    if (fileInputs !== null) {
        for (var i = 0; i < fileInputs.length; i++) {
            fileInputs[i].addEventListener("change", inputFileChange);
        }
    }

    var okBtn = document.getElementById("ok-btn");
    if (okBtn !== null) {
        okBtn.onclick = submitForm;
    }

    var deleteAccountPasswordField = document.getElementById("password-field-delete-account");
    deleteAccountPasswordField.onkeyup = deletingAccountPassword;
}

window.onload = pageLoad;

// TODO: change to pure JS
function toggleContent() {
    var $ele = $(this);
    var $contentDiv = $ele.next("div.more-content");
    if ($contentDiv.is(":hidden")) {
        $ele.text("Hide Content!");
        $contentDiv.show();
    } else {
        $ele.text("Show Content!");
        $contentDiv.hide();
    }
}

function checkNumber() {
    var field = document.getElementById("input-phone-number");
    var valid = document.getElementById("input-phone-number-invalid");
    if (isNaN(field.value) || field.value.length < 8 || field.value.indexOf(".") > -1) {
        valid.innerHTML = "Please enter a valid phone number.";
    } else {
        valid.innerHTML = "";
    }
}

function checkName() {
    var first = document.getElementById("input-first-name");
    var last = document.getElementById("input-last-name");
    var valid = document.getElementById("input-name-invalid");
    if (TEXT_REGEX.test(first.value) && first.value.length > 1 && TEXT_REGEX.test(last.value) && last.value.length > 1) {
        valid.innerHTML = "";
    } else {
        valid.innerHTML = "Please type your real name with English letters only.";
    }
}

function checkEmail() {
    var field = document.getElementById("input-email");
    var valid = document.getElementById("input-email-invalid");
    if (EMAIL_REGEX.test(field.value)) {
        valid.innerHTML = "";
    } else {
        valid.innerHTML = "Please enter a valid email.";
    }
}

function checkPassword() {
    var field = document.getElementById("input-password");
    var valid = document.getElementById("input-password-invalid");
    if (field.value.length < MIN_PASSWORD_LENGTH || field.value.indexOf(" ") > -1) {
        valid.innerHTML = "Password must be at least 8 characters. No spaces!";
    } else {
        valid.innerHTML = "";
    }
}

function checkConfirmPassword() {
    var password = document.getElementById("input-password");
    var confirmPassword = document.getElementById("input-confirm-password");
    var valid = document.getElementById("input-password-invalid");
    if (password.value != confirmPassword.value) {
        valid.innerHTML = "Passwords don't match.";
    } else if (password.value.length < MIN_PASSWORD_LENGTH || password.value.indexOf(" ") > -1) {
        valid.innerHTML = "Password must be at least 8 characters. No spaces!";
    } else {
        valid.innerHTML = "";
    }
}

function checkAccountTypeInput() {
    var studentInput = document.getElementById("input-type-student");
    var tutorInput = document.getElementById("input-type-tutor");
    if ((studentInput.checked === false && tutorInput.checked === false) || (studentInput.value !== "Student" || tutorInput.value !== "Tutor")) {
        document.getElementById("input-account-type-invalid").innerHTML = "Please select your account type.";
    } else {
        document.getElementById("input-account-type-invalid").innerHTML = "";
    }
}

function checkGenderInput() {
    var maleInput = document.getElementById("input-gender-male");
    var femaleInput = document.getElementById("input-gender-female");
    if ((maleInput.checked === false && femaleInput.checked === false) || (maleInput.value !== "Male" || femaleInput.value !== "Female")) {
        document.getElementById("input-gender-invalid").innerHTML = "Please select your gender.";
    } else {
        document.getElementById("input-gender-invalid").innerHTML = "";
    }
}

function checkValidSignupForm() {
    checkAccountTypeInput();
    checkGenderInput();
    var valid = document.getElementsByClassName("invalid");
    for (var i = 0; i < valid.length; i++) {
        if (valid[i].innerHTML.trim() !== "") {
            BootstrapDialog.alert("Please enter valid information.");
            return false;
        }
    }
    return true;
}

function deletingAccountPassword() {
    var submitDelete = document.getElementById("submit-delete");
    if (this.value.trim() === "") {
        submitDelete.classList.remove("btn-danger");
        submitDelete.classList.add("btn-default");
    } else {
        submitDelete.classList.remove("btn-default");
        submitDelete.classList.add("btn-danger");
    }
}

function disabledFeature() {
    BootstrapDialog.alert('This feature has been postponed for version 2.');
    return false;
}

function confirmDeleteAccount() {
    return BootstrapDialog.confirm({
        title: 'DELETE ACCOUNT?',
        message: 'Do you really want to delete your account?\nAll your information will be removed!',
        type: BootstrapDialog.TYPE_DANGER,
        closable: true,
        btnCancelLabel: 'Do not delete it!',
        btnOKLabel: 'Delete it!',
        btnOKClass: 'btn-danger',
        callback: function (result) {
            // result is true if confirmed, and false if dialog is closed
            if (result) {
                document.getElementById("delete-account-form").submit();
            }
        }
    });
}

function confirmDeleteMsg() {
    return BootstrapDialog.confirm("Are you sure you want to delete this message?", function (result) {
        if (result) {
            document.getElementById("delete-msg-form").submit();
        }
    });
}

function confirmChanges() {
    return BootstrapDialog.confirm("Do you want to want to save changes?", function (result) {
        if (result) {
            document.getElementById("change-profile-form").submit();
        }
    });
}

function resetPassword() {
    BootstrapDialog.show({
        title: "Reset Password",
        type: BootstrapDialog.TYPE_PRIMARY,
        message: $("<div></div>").load("views/reset_password_form.html"),
        buttons: [{
            label: "Email Me Reset Link!",
            cssClass: "btn-primary",
            //hotkey: 13, // Enter.
            action: function () {
                if ($("#user-email").val().match(EMAIL_REGEX)) {
                    $("#reset-password-form").submit();
                } else {
                    $("#invalid-err").text("Please enter a valid email address!");
                }
            }
        }, {
            label: "Cancel",
            cssClass: "btn-default",
            action: function (dialog) {
                dialog.close();
            }
        }]
    });
}

function contactFormValidation(event) {
    event.preventDefault();
    var invalid = false;
    // TODO: really??! fix
    var formInputs = (document.getElementById("contact-form")).getElementsByTagName("input");
    for (var i = 0; i < formInputs.length; i++) {
        if (formInputs[i].value.trim() === "") {
            invalid = true;
            break;
        }
    }
    if (invalid || document.querySelector("textarea[name='comment']").value.trim() === "") {
        BootstrapDialog.alert("Please enter valid information!");
        return false;
    }
    // document.getElementById("confirmbox").modal = "show";
    $("#confirmbox").modal("show");
}

function profileFormValidation(event) {
    event.preventDefault();
    var currentPassword = document.getElementById("current-password");
    if (currentPassword.value.trim() === "") {
        BootstrapDialog.alert("Please enter your current password to update your profile info.");
        return false;
    }
    event.target.submit();
}

// TODO: horrible. lel
function submitForm() {
    var form = document.querySelector("form");
    form.submit();
}

function search() {
    var resultEle = document.getElementById("search-result");
    if (this.value === "") {
        resultEle.innerHTML = "";
        return;
    }
    var ajaxReq;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        ajaxReq = new XMLHttpRequest();
    } else { // code for IE6, IE5
        ajaxReq = new ActiveXObject("Microsoft.XMLHTTP");
    }

    ajaxReq.onreadystatechange = function () {
        if (ajaxReq.readyState == 4 && ajaxReq.status == 200) {
            if (ajaxReq.responseText.trim() !== "") {
                resultEle.innerHTML = "";
                var jobj = ajaxReq.responseText;
                displayCourses(jobj);
            }
        }
    };
    ajaxReq.open("get", "get_info.php?search=" + this.value, true);
    ajaxReq.send();
}

function displayCourses(jsonObj) {
    jsonObj = JSON.parse(jsonObj);
    for (var i = 0; i < jsonObj.length; i++) {
        var item = document.createElement("div");
        var row = document.createElement("div");
        row.className = "col-sm-6";
        item.className = "list-group-item clearfix";


        // or jsonObj.i.subject_name but that didn't work
        item.innerHTML = "<div>" + jsonObj[i]["subject_name"] + " " + jsonObj[i]["course_number"] +
            "</div><div class='pull-right clearfix'>" +
            "<form method='get' action='course_tutors'>" +
            "<input type='hidden' name='subject_name' value='" + jsonObj[i]["subject_name"].toLowerCase() + "' />" +
            "<input type='hidden' name='course_number' value='" + jsonObj[i]["course_number"] + "' />" +
            "<input type='submit' value='Submit' class='btn btn-default' />" +
            "</form></div>";
        row.appendChild(item);
        document.getElementById("search-result").appendChild(row);
    }
}

function inputFileChange() {
    var fileInput = this;
    var fileBtn = fileInput.parentNode;
    var fileName = fileBtn.parentNode.querySelector(".filename");
    var filePath = fileInput.value.trim();
    if (filePath === "") {
        fileBtn.classList.remove('btn-primary');
        fileName.innerHTML = "No file chosen";
    } else {
        var separatorIdx = filePath.lastIndexOf("/");
        if (separatorIdx === -1) {
            separatorIdx = filePath.lastIndexOf("\\");
        }
        fileBtn.classList.add('btn-primary');
        fileName.innerHTML = filePath.substring(separatorIdx + 1);
    }
}

var getElementsByClass = function (className) {
    if (document.getElementsByClassName) {
        getElementsByClass = function (className) {
            return document.getElementsByClassName(className);
        };
    } else {

    }
};

/*
	Developed by Robert Nyman, http://www.robertnyman.com
	Code/licensing: http://code.google.com/p/getelementsbyclassname/
*/
var getElementsByClassName = function (className, tag, elm) {
    if (document.getElementsByClassName) {
        getElementsByClassName = function (className, tag, elm) {
            elm = elm || document;
            var elements = elm.getElementsByClassName(className),
                nodeName = (tag) ? new RegExp("\\b" + tag + "\\b", "i") : null,
                returnElements = [],
                current;
            for (var i = 0, il = elements.length; i < il; i += 1) {
                current = elements[i];
                if (!nodeName || nodeName.test(current.nodeName)) {
                    returnElements.push(current);
                }
            }
            return returnElements;
        };
    } else if (document.evaluate) {
        getElementsByClassName = function (className, tag, elm) {
            tag = tag || "*";
            elm = elm || document;
            var classes = className.split(" "),
                classesToCheck = "",
                xhtmlNamespace = "http://www.w3.org/1999/xhtml",
                namespaceResolver = (document.documentElement.namespaceURI === xhtmlNamespace) ? xhtmlNamespace : null,
                returnElements = [],
                elements,
                node;
            for (var j = 0, jl = classes.length; j < jl; j += 1) {
                classesToCheck += "[contains(concat(' ', @class, ' '), ' " + classes[j] + " ')]";
            }
            try {
                elements = document.evaluate(".//" + tag + classesToCheck, elm, namespaceResolver, 0, null);
            } catch (e) {
                elements = document.evaluate(".//" + tag + classesToCheck, elm, null, 0, null);
            }
            while ((node = elements.iterateNext())) {
                returnElements.push(node);
            }
            return returnElements;
        };
    } else {
        getElementsByClassName = function (className, tag, elm) {
            tag = tag || "*";
            elm = elm || document;
            var classes = className.split(" "),
                classesToCheck = [],
                elements = (tag === "*" && elm.all) ? elm.all : elm.getElementsByTagName(tag),
                current,
                returnElements = [],
                match;
            for (var k = 0, kl = classes.length; k < kl; k += 1) {
                classesToCheck.push(new RegExp("(^|\\s)" + classes[k] + "(\\s|$)"));
            }
            for (var l = 0, ll = elements.length; l < ll; l += 1) {
                current = elements[l];
                match = false;
                for (var m = 0, ml = classesToCheck.length; m < ml; m += 1) {
                    match = classesToCheck[m].test(current.className);
                    if (!match) {
                        break;
                    }
                }
                if (match) {
                    returnElements.push(current);
                }
            }
            return returnElements;
        };
    }
    return getElementsByClassName(className, tag, elm);
};
