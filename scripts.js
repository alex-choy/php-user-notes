function change_encrypter() {
  var encrypter_options_form = document.getElementById("encrypter_options");
  var new_action = encrypter_options_form.value + ".php";
  var encryption_form = document.getElementById("encrypter_form");
  // old_action = encryption_form.action;
  encryption_form.action = new_action;
  // alert(new_action);

  show_one_key_option(encrypter_options_form.value);

  // if(encrypter_options_form.value == "simple_substitution") {
  // 	var secret_key = document.getElementById("secret_key");
  // 	secret_key.value = get_rand_key_16();
  // }

  // var secret_key_form = document.getElementById("secret_label");
  // if(encrypter_options_form.value == "rc4") {
  // 	secret_key_form.hidden = false;
  // } else {
  // 	secret_key_form.hidden = true;
  // }
}

function show_one_key_option(key_to_show) {
  var different_keys = document.getElementById("different_keys").children;

  for (i = 0; i < different_keys.length; i++) {
    temp_key = different_keys[i];
    if (temp_key.getAttribute("name").includes(key_to_show)) {
      temp_key.hidden = false;
    } else temp_key.hidden = true;
  }
}

function new_sub_key() {
  var sub_key = document.getElementById("sub_key");
  sub_key.value = get_rand_key_simple_sub();
  document.getElementById("change_secret_key").hidden = true;
}

function get_rand_key_simple_sub() {
  key_len = 26;
  var alphabet = "abcdefghijklmnopqrstuvwxyz".split("");

  for (var i = 0; i < key_len; i++) {
    rand_index = Math.floor(Math.random() * key_len) % key_len;
    var temp = alphabet[i];
    alphabet[i] = alphabet[rand_index];
    alphabet[rand_index] = temp;
  }
  return alphabet.join("");
}

function e_or_d_change(radio) {
  var chosen_e_or_d = radio.id;
  var enc_or_dec_header = document.getElementById("enc_or_dec_header");
  enc_or_dec_header.innerHTML =
    "What kind of " + radio.id + "ion would you like to do?";
}

function validateSignup(signup_form) {
  var email = signup_form.signup_em.value;
  var username = signup_form.signup_un.value;
  var password = signup_form.signup_pw.value;

  fail = validateEmail(email);
  fail += validateUsername(username);
  fail += validatePassword(password);

  if (fail == "") {
    return true;
  }
  alert(fail);
  return false;
}

function validateEmail(email) {
  var valid_email_regex = /[^a-zA-Z0-9.@_-]/;

  if (
    !(email.indexOf(".") > 0 && email.indexOf("@") > 0) ||
    valid_email_regex.test(email)
  ) {
    return "Email is not valid. Please enter a valid email address.\n";
  }
  return "";
}

function validateUsername(username) {
  min_username_len = 5;
  valid_username_regex = /[^a-zA-Z0-9_-]/;

  if (username.length < min_username_len) {
    return (
      "Username needs to be longer than " + min_username_len + " characters\n"
    );
  } else if (valid_username_regex.test(username)) {
    return "Username is not valid. Permitted characters: a-z, A-Z, 0-9, -, and _.\n";
  }
  return "";
}

function validatePassword(password) {
  var min_password_len = 6;
  if (password.length < min_password_len) {
    return (
      "Password needs to be longer than " + min_password_len + " characters.\n"
    );
  } else if (
    !/[a-z]/.test(password) ||
    !/[A-Z]/.test(password) ||
    !/[0-9]/.test(password)
  ) {
    return "Password is invalid. Must contain at least one of each: a-z, A-Z, and 0-9.\n";
  }
  return "";
}

function decrypt_text(tr) {
  var cells = tr.cells;

  var encryption_type = cells[1].innerHTML;
  var key1 = cells[2].innerHTML;
  var content = cells[4].innerHTML;
  var decrypted_text = "";

  if (encryption_type == "Simple Substitution") {
    decrypted_text = decrypt_simple_sub(key1, content);
  } else if (encryption_type == "Double Transposition") {
    var key2 = cells[3].innerHTML;
    decrypted_text = decrypt_double_trans(key2, key1, content);
  }

  var message =
    encryption_type + " for ID " + cells[0].innerHTML + ": " + decrypted_text;

  var the_div = document.getElementById("decrypted_message_space");
  the_div.hidden = false;
  the_div.innerHTML = message;
}

function decrypt_simple_sub(key, text) {
  var alpha = "abcdefghijklmnopqrstuvwxyz";
  var decrypted_text = "";

  for (var i = 0; i < text.length; i++) {
    var temp_text = text[i];
    if (temp_text != " ") {
      var index_in_alpha = key.indexOf(temp_text);
      decrypted_text += alpha[index_in_alpha];
    } else {
      decrypted_text += " ";
    }
  }

  return decrypted_text;
}

function decrypt_double_trans(key1, key2, text) {
  var key_1_arr = key1.split(" ");
  var key_2_arr = key2.split(" ");
  var text_arr = text.split("");

  // Remove the empty space that split() produced
  console.log("1: " + key_1_arr + "; 2: " + key_2_arr);

  // console.log("1: " + key_1_arr.length + ", 2: " + key_2_arr.length);

  var shuffled_text = [];
  var deciphered_text = [];

  // Fill up the shuffled text array, initialize deciphered text array
  for (var i = 0; i < key_1_arr.length; i++) {
    var temp_arr = [];
    var decipher_temp_arr = [];
    for (var j = 0; j < key_2_arr.length; j++) {
      var curr_index = i * key_2_arr.length + j;
      var temp = text_arr[curr_index];

      if (temp == " ") {
        temp_arr[j] = " ";
      } else {
        temp_arr[j] = temp;
      }
      decipher_temp_arr[j] = " ";
    }
    console.log("Temp: " + temp_arr);
    shuffled_text.push(temp_arr);
    deciphered_text.push(decipher_temp_arr);
  }

  // Decrypt the shuffled text array and put them inside the deciphered text
  for (var i = 0; i < key_1_arr.length; i++) {
    shuff_row = key_1_arr[i];
    for (var j = 0; j < key_2_arr.length; j++) {
      shuff_col = key_2_arr[j];
      deciphered_text[shuff_row][shuff_col] = shuffled_text[i][j];
    }
  }

  var decrypted_message = "";
  for (var i = 0; i < deciphered_text.length; i++) {
    for (var j = 0; j < deciphered_text[0].length; j++) {
      var temp_char = deciphered_text[i][j];
      if (temp_char != " ") {
        decrypted_message += temp_char;
      }
    }
  }

  return decrypted_message;
}

function show_rc4(text, id) {
  var message = "RC4 encryption for ID " + id + ": " + text;

  var the_div = document.getElementById("decrypted_message_space");
  the_div.hidden = false;
  the_div.innerHTML = message;
}
