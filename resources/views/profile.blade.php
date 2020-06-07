@extends('layout.index')

@section('style')
    <style>
        #profile {
            background: #eaeaea;
        }

        .profile-container {
            padding: 40px 25px;
        }

        .profile-container > .custom-container {
            background: white;
            height: 100%;
            padding: 20px 20px 100px 20px;
            border-radius: 15px;
        }

        .profile-container > .custom-container > .header {
            display: flex;
            justify-content: space-between;
        }

        .profile-container > .custom-container > .header > .title {
            font-weight: 500;
        }

        .profile-container > .custom-container > .header > .edit {
            cursor: pointer;
        }

        .profile-container > .custom-container > #profile-data > form > .data,
        .profile-container > .custom-container > #edit-password > form > .data {
            display: flex;
            margin-bottom: 30px;
        }

        .profile-container > .custom-container > #profile-data > form > .data > .name,
        .profile-container > .custom-container > #edit-password > form > .data > .name {
            flex: 1;
        }

        .profile-container > .custom-container > #profile-data > form > .data > .value,
        .profile-container > .custom-container > #profile-data > form > .data > .value-image {
            flex: 2;
        }

        .profile-container > .custom-container > #profile-data > form > .data > select {
            flex: 2;
            height: 38px;
            border: 1px solid black;
            text-indent: 8px;
        }

        .profile-container > .custom-container > #profile-data > form > .data > input,
        .profile-container > .custom-container > #edit-password > form > .data > input {
            flex: 2;
            width: 100%;
            padding: 6px 0;
            border: 1px solid black;
            text-indent: 8px;
        }

        .profile-container > .custom-container > #profile-data > form > .data > .value-image > #profile-image {
            width: 25%;
        }

        .profile-container > .custom-container > #profile-data > form > .data > .value-image > #profile-image.is-edit {
            cursor: pointer;
        }

        .input-form {
            display: none;
        }

        .save {
            display: none;
            cursor: pointer;
        }

        #edit-password {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div id="profile">
        <div class="profile-container">
            <div class="custom-container">
                <div class="header">
                    <div class="title">
                        PROFILE
                    </div>
                    <div class="edit" onclick="edit()">
                        <span>
                            Edit <i class="fa fa-edit"></i>
                        </span>
                    </div>
                    <div class="save">
                        <span onclick="cancel()">
                            Cancel
                        </span>
                         |
                        <span onclick="save()">
                            Save <i class="fa fa-save"></i>
                        </span>
                    </div>
                </div>
                <hr>
                <div id="profile-image-url" data-image="{{ $user['image_url'] }}" hidden></div>
                <div id="profile-data">
                    <form action="{{ route('edit-profile') }}" method="POST" enctype="multipart/form-data" id="form-profile-data">
                        {{ csrf_field() }}

                        <div class="data">
                            <div class="name">Name</div>
                            <div class="value">{{ $user['name'] }}</div>
                            <input type="text" class="input-form" placeholder="name" value="{{ $user['name'] }}" name="name" required>
                        </div>
                        <div class="data">
                            <div class="name">Email</div>
                            <div class="value">{{ $user['email'] }}</div>
                            <input type="email" class="input-form" placeholder="name" value="{{ $user['email'] }}" name="email" required>
                        </div>
                        <div class="data">
                            <div class="name">Gender</div>
                            <div class="value">{{ $user['gender'] }}</div>
                            <select name="gender" class="input-form" required>
                                <option value="male" {{ $user['gender'] == "male" ? "selected" : "" }}>male</option>
                                <option value="female" {{ $user['gender'] == "female" ? "selected" : "" }}>female</option>
                            </select>
                        </div>
                        <div class="data">
                            <div class="name">Address</div>
                            <div class="value">{{ $user['address'] }}</div>
                            <input type="text" class="input-form" placeholder="name" value="{{ $user['address'] }}" name="address" required>
                        </div>
                        <div class="data">
                            <div class="name">Date of Birth</div>
                            <div class="value">{{ \Carbon\Carbon::createFromDate($user['date_of_birth'])->format("d M Y") }}</div>
                            <input type="date" class="input-form" value="{{ $user['date_of_birth'] }}" name="date_of_birth" required>
                        </div>
                        <div class="data">
                            <div class="name">Image</div>
                            <div class="value-image" onclick="triggerInputImageFile()">
                            @if($user['image_url'] == null)
                                <img src="{{ asset('default-profile.png') }}" alt="profile image" id="profile-image">
                            @else
                                <img src="{{ $user['image_url'] }}" alt="profile image" id="profile-image">
                            @endif
                            </div>
                            <input type="file" id="input-image-file" class="input-form" name="image_url" accept="image/*" hidden>
                        </div>
                    </form>
                </div>
                <div id="edit-password">
                    <form action="{{ route('edit-password') }}" method="POST" id="form-edit-password">
                        {{ csrf_field() }}

                        <div class="data">
                            <div class="name">New Password</div>
                            <input type="password" class="password-class" name="password" placeholder="password" required>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let isEdit = false;
        let editType;
        let profileImage;

        $(() => {
            $("#input-image-file").change((data) => {
                readURL(data.target);
            });

            profileImage = $("#profile-image-url").data("image")
        })

        function save() {
            if(editType == "password") {
                return savePassword()
            }

            let validity = true

            let inputForms = $('.input-form')
            for(let i = 0; i < inputForms.length; i++) {
                if(! inputForms[i].checkValidity()) validity = false
            }

            if(validity) {
                isEdit = false

                $(".value").css('display', 'block')
                $(".input-form").css('display', 'none')
                $(".save").css('display', 'none')
                $(".edit").css('display', 'block')
                $("#profile-image").removeClass("is-edit")

                $("#form-profile-data").submit()
            }else {
                let form = document.querySelector('#form-profile-data')

                form.reportValidity()
            }

        }

        function cancel() {
            if(editType == "password") {
                $(".save").css('display', 'none')
                $(".edit").css('display', 'block')
                $("#profile-data").css('display', 'block')
                $("#edit-password").css('display', 'none')
            }else if(editType == "profile") {
                isEdit = false

                $('#profile-image').attr('src', profileImage)
                $(".value").css('display', 'block')
                $(".input-form").css('display', 'none')
                $(".save").css('display', 'none')
                $(".edit").css('display', 'block')
                $("#profile-image").removeClass("is-edit")
            }
        }

        function edit() {
            swal("Sure! What do you want to change?", {
                buttons: {
                    profile: {
                        text: "Profile",
                        catch: "profile"
                    },
                    password: {
                        text: "Password",
                        catch: "password"
                    }
                },
            })
            .then((value) => {
                switch (value) {
                    case "profile":
                        editType = "profile"
                        editProfile()
                        break;

                    case "password":
                        editType = "password"
                        editPassword()
                        break;
                };
            })

        }

        function editProfile() {
            isEdit = true

            $("#profile-image").addClass("is-edit")
            $(".value").css('display', 'none')
            $(".input-form").css('display', 'block')
            $(".save").css('display', 'block')
            $(".edit").css('display', 'none')
        }

        function editPassword() {
            $(".save").css('display', 'block')
            $(".edit").css('display', 'none')
            $("#profile-data").css('display', 'none')
            $("#edit-password").css('display', 'block')
        }

        function savePassword() {
            let passwordClass = $('.password-class')[0].checkValidity()
            if(passwordClass) {
                $(".save").css('display', 'none')
                $(".edit").css('display', 'block')
                $("#profile-data").css('display', 'block')
                $("#edit-password").css('display', 'none')

                $("#form-edit-password").submit()
            }else {
                let form = document.querySelector('#form-edit-password')

                form.reportValidity()
            }
        }

        function triggerInputImageFile() {
            if(isEdit) $("#input-image-file").trigger('click')
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader()

                reader.onload = (e) => {
                    $('#profile-image').attr('src', e.target.result)
                }

                reader.readAsDataURL(input.files[0])
            }
        }
    </script>
@endsection
