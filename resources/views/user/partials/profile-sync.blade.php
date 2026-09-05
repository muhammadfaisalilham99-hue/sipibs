<script>
    (function () {
        function applySipibsProfile() {
            try {
                var userData = JSON.parse(localStorage.getItem('sipibs_user_profile') || 'null');
                var oldUserData = JSON.parse(localStorage.getItem('sipibs_profile') || 'null');
                var adminData = JSON.parse(localStorage.getItem('adminProfileData') || 'null');
                var isUserPage = !!document.querySelector('.user-sidebar');
                var data = isUserPage ? (userData || oldUserData) : adminData;
                if (!data) return;

                var name = data.name || data.nama || '';
                var email = data.email || '';
                var phone = data.phone || data.telp || '';
                var address = data.address || data.alamat || '';
                var photo = data.photo || data.photoUrl || '';
                var nip = data.nip || '';
                var username = data.username || '';
                var className = data.className || data.kelas || '';
                var nis = data.nis || '';
                var birthdate = data.birthdate || data.tanggalLahir || '';
                var gender = data.gender || data.jenisKelamin || '';

                if (name) {
                    var nameEls = document.querySelectorAll('[data-profile-name]');
                    nameEls.forEach(function(el) { el.textContent = name; });
                    var topUserName = document.getElementById('top-user-name');
                    if (topUserName) topUserName.textContent = name;
                    if (topUserName && topUserName.nextElementSibling && isUserPage) {
                        topUserName.nextElementSibling.textContent = 'SISWA';
                    }
                    var topAdminName = document.getElementById('topAdminName');
                    if (topAdminName) topAdminName.textContent = name;
                    var heroGreeting = document.getElementById('hero-greeting-name');
                    if (heroGreeting) heroGreeting.textContent = name;
                    var heroName = document.getElementById('hero-name');
                    if (heroName) heroName.textContent = name;
                    var inputName = document.getElementById('input-name');
                    if (inputName) inputName.value = name;
                    var inputNama = document.getElementById('inputNama');
                    if (inputNama) inputNama.value = name;
                    var displayAdminName = document.getElementById('displayAdminName');
                    if (displayAdminName) displayAdminName.textContent = name;
                }

                if (nip) {
                    var displayNip = document.getElementById('displayNip');
                    if (displayNip) displayNip.textContent = nip;
                    var inputNip = document.getElementById('inputNip');
                    if (inputNip) inputNip.value = nip;
                }

                if (username) {
                    var inputUsername = document.getElementById('input-username');
                    if (inputUsername) inputUsername.value = username;
                }

                if (className) {
                    var inputClass = document.getElementById('input-class');
                    if (inputClass) inputClass.value = className;
                }

if (nis) {
                    var inputNis = document.getElementById('input-nis');
                    if (inputNis) inputNis.value = nis;
                }

                if (birthdate) {
                    var inputBirthdate = document.getElementById('input-birthdate');
                    if (inputBirthdate) inputBirthdate.value = birthdate;
                }

                if (gender) {
                    var inputGender = document.getElementById('input-gender');
                    if (inputGender) inputGender.value = gender;
                }

                if (email) {
                    var heroEmail = document.getElementById('hero-email');
                    if (heroEmail) heroEmail.textContent = email;
                    var inputEmail = document.getElementById('input-email');
                    if (inputEmail) inputEmail.value = email;
                    var inputEmail2 = document.getElementById('inputEmail');
                    if (inputEmail2) inputEmail2.value = email;
                    var displayEmail = document.getElementById('displayEmail');
                    if (displayEmail) displayEmail.textContent = email;
                }

                if (phone) {
                    var heroPhone = document.getElementById('hero-phone');
                    if (heroPhone) heroPhone.textContent = phone;
                    var inputPhone = document.getElementById('input-phone');
                    if (inputPhone) inputPhone.value = phone;
                    var inputTelp = document.getElementById('inputTelp');
                    if (inputTelp) inputTelp.value = phone;
                }

                if (address) {
                    var heroAddress = document.getElementById('hero-address');
                    if (heroAddress) heroAddress.textContent = address;
                    var inputAddress = document.getElementById('input-address');
                    if (inputAddress) inputAddress.value = address;
                    var inputAlamat = document.getElementById('inputAlamat');
                    if (inputAlamat) inputAlamat.value = address;
                }

                if (photo) {
                    document.querySelectorAll('.avatar-target').forEach(function(el) {
                        el.src = photo;
                    });
                    var topAvatar = document.getElementById('top-avatar');
                    if (topAvatar) topAvatar.src = photo;
                    var profilePhoto = document.getElementById('profile-photo');
                    if (profilePhoto) profilePhoto.src = photo;
                    var profilePhotoDisplay = document.getElementById('profilePhotoDisplay');
                    if (profilePhotoDisplay) profilePhotoDisplay.src = photo;
                }
            } catch (e) {}
        }
        applySipibsProfile();
        window.applySipibsProfile = applySipibsProfile;
    })();
</script>

