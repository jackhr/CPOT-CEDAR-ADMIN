<?php include_once __DIR__ . "/../../partials/header.php"; ?>
<?php include_once __DIR__ . "/../../partials/navbar.php"; ?>

<main>
    <div class="table-wrapper">
        <button id="create-user-btn" class="create-btn continue-btn open-modal-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <line x1="19" x2="19" y1="8" y2="14" />
                <line x1="22" x2="16" y1="11" y2="11" />
            </svg>
            <span>Create User</span>
        </button>
        <table id="users-table">
            <thead>
                <tr>
                    <th>Id #</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <?php if ($user['role_id'] === 1) { ?>
                        <th>Deleted At</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u) {
                    $created_at = new DateTime($u['created_at']);
                    $updated_at = new DateTime($u['updated_at']);
                    $created_at = $created_at->format('M j, Y \@ g:i A T');
                    $updated_at = $updated_at->format('M j, Y \@ g:i A T');

                    if ($user['role_id'] === 1) {
                        $deleted_at = "-";
                        if (isset($u['deleted_at'])) {
                            $deleted_at = new DateTime($u['deleted_at']);
                            $deleted_at = $deleted_at->format('M j, Y \@ g:i A T');
                        }
                    }
                ?>
                    <tr data-id="<?php echo $u['user_id']; ?>">
                        <td><?php echo $u['user_id']; ?></td>
                        <td><?php echo $u['username']; ?></td>
                        <td><?php echo $u['email']; ?></td>
                        <td data-id="<?php echo $u['role_id']; ?>"><?php echo $u['role_name']; ?></td>
                        <td class="dt-type-date"><?php echo $created_at; ?></td>
                        <td class="dt-type-date"><?php echo $updated_at; ?></td>
                        <?php if ($user['role_id'] === 1) { ?>
                            <td class="dt-type-date"><?php echo $deleted_at; ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<div id="create-user-modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-options">
                    <span class="modal-close">×</span>
                </div>
                <h1>Adding User</h1>
            </div>
            <div class="modal-body">
                <form id="create-user-form">
                    <div class="input-container">
                        <label for="username">Username</label>
                        <input type="text" name="username" placeholder="NewUser1" pattern="\w{5,}" required>
                    </div>
                    <div class="input-container">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="new_user@gmail.com" required>
                    </div>
                    <div class="input-container">
                        <label for="role">Role</label>
                        <select name="role" required>
                            <option selected disabled value="">Select A Role</option>
                            <?php foreach ($roles as $r) {
                                echo "<option value=\"{$r['role_id']}\">{$r['role_name']}</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="input-container password-container">
                        <label for="new-password">New Password</label>
                        <input type="password" name="new-password" required>
                        <div class="password-eye-container">
                            <svg class="show-pass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg class="hide-pass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
                                <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                                <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
                                <path d="m2 2 20 20" />
                            </svg>
                        </div>
                    </div>
                    <div class="input-container password-container">
                        <label for="confirm-new-password">Confirm New Password</label>
                        <input type="password" name="confirm-new-password" required>
                        <div class="password-eye-container">
                            <svg class="show-pass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg class="hide-pass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
                                <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                                <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
                                <path d="m2 2 20 20" />
                            </svg>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button form="create-user-form" type="submit" class="continue-btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<div id="edit-user-modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-options">
                    <span class="modal-close">×</span>
                </div>
                <h1>Editing User</h1>
            </div>
            <div class="modal-body">
                <form id="edit-user-form">
                    <div class="input-container">
                        <label>Id #</label>
                        <span id="edit-user-id"></span>
                    </div>
                    <hr style="border: solid 0.5px #d3d3d3;margin: 24px 0;">
                    <div class="input-container">
                        <label for="username">Username</label>
                        <input type="text" name="username" placeholder="CurrentUser" pattern="\w{5,}" required>
                    </div>
                    <div class="input-container">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="current_user@gmail.com" required>
                    </div>
                    <div class="input-container">
                        <label for="role">Role</label>
                        <select name="role" required>
                            <option disabled value="">Select A Role</option>
                            <?php foreach ($roles as $r) {
                                echo "<option value=\"{$r['role_id']}\">{$r['role_name']}</option>";
                            } ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="continue-btn cancel">Cancel</button>
                <button form="edit-user-form" type="submit" class="continue-btn">Update</button>
            </div>
            <div class="modal-footer" style="margin-top: 18px;">
                <button class="continue-btn danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        new DataTable("#users-table", {
            ...STATE.dtDefaultOpts,
        });

        $(".create-btn").on("click", () => $("#create-user-modal").addClass("showing"))

        $(".password-container input").on("input", function() {
            const {
                "new-password": newPass,
                "confirm-new-password": confirmNewPass,
            } = $("#create-user-form").serializeObject();

            if (!newPass.length || !confirmNewPass.length) {
                // no need to validate if no value
                $(".password-container input").removeClass("error success");
                return;
            }

            const differentPasswords = newPass !== confirmNewPass;
            const passwordIsTooShort = newPass.length < 5;
            const toggleSuccess = !differentPasswords && !passwordIsTooShort;

            $(".password-container input")
                .toggleClass("error", (differentPasswords || passwordIsTooShort))
                .toggleClass("success", toggleSuccess);
        });

        $('button[form="edit-user-form"]').on("click", function(e) {
            e.preventDefault();

            const form = $("#edit-user-form");
            const data = form.serializeObject();
            data.user_id = $("#edit-user-id").text();
            const formValidationMsg = getFormValidationMsg(data, "edit");

            if (formValidationMsg) {
                return Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: formValidationMsg
                });
            }

            Swal.fire({
                title: "Loading...",
                html: `Editing user, <strong>"${data.username}"</strong>.`,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/users/${data.user_id}`,
                method: "PUT",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify(data),
                success: res => {
                    const {
                        data,
                        status,
                        message
                    } = res;
                    const success = status === 200;

                    Swal.fire({
                        icon: success ? "success" : "error",
                        title: success ? "Success" : "Error",
                        text: message,
                    }).then(() => {
                        success && location.reload();
                    });
                },
                error: function() {
                    console.log("arguments:", arguments);
                }
            });
        });

        $('button[form="create-user-form"]').on("click", function(e) {
            e.preventDefault();

            const form = $("#create-user-form");
            const data = form.serializeObject();
            const formValidationMsg = getFormValidationMsg(data);

            if (formValidationMsg) {
                return Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: formValidationMsg
                });
            }

            Swal.fire({
                title: "Loading...",
                html: `Creating user, <strong>"${data.username}"</strong>.`,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "/users",
                method: "POST",
                dataType: "JSON",
                data,
                success: res => {
                    const {
                        data,
                        status,
                        message
                    } = res;
                    const success = status === 200;

                    Swal.fire({
                        icon: success ? "success" : "error",
                        title: success ? "Success" : "Error",
                        text: message,
                    }).then(() => {
                        success && location.reload();
                    });
                },
                error: function() {
                    console.log("arguments:", arguments);
                }
            });
        });

        $("#users-table tbody tr").on("click", function() {
            const modal = $("#edit-user-modal");
            const userId = $(this).find('td').eq(0).text();
            const username = $(this).find('td').eq(1).text();
            const email = $(this).find('td').eq(2).text();
            const roleId = $(this).find('td').eq(3).data('id');

            modal.find('#edit-user-id').text(userId);
            modal.find('input[name="username"]').val(username);
            modal.find('input[name="email"]').val(email);
            modal.find('select[name="role"]').prop('selectedIndex', roleId);

            modal.addClass("showing");
        });

        $("#edit-user-modal button.cancel").on("click", function() {
            $(this).closest('.modal').removeClass('showing');
        });

        $("#edit-user-modal button.danger").on("click", async function() {
            const form = $("#edit-user-form");
            const data = form.serializeObject();
            data.user_id = $("#edit-user-id").text();

            const res = await Swal.fire({
                icon: "warning",
                title: `Deleting "${data.username}"`,
                text: "Are you sure that you would like to delete this user?",
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: 'No'
            });

            if (!res.isConfirmed) return;

            Swal.fire({
                title: "Loading...",
                html: `Deleting user, <strong>"${data.username}"</strong>.`,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/users/${data.user_id}`,
                method: "DELETE",
                dataType: "JSON",
                data,
                success: res => {
                    const {
                        data,
                        status,
                        message
                    } = res;
                    const success = status === 200;

                    Swal.fire({
                        icon: success ? "success" : "error",
                        title: success ? "Success" : "Error",
                        text: message,
                    }).then(() => {
                        success && location.reload();
                    });
                },
                error: function() {
                    console.log("arguments:", arguments);
                }
            });
        });
    });

    function getFormValidationMsg(data, type = "create") {
        let errMsg = "";

        if (type === "create" || type === "edit") {
            if (data.username.length < 5) {
                errMsg = "A user's username must be at least 5 characters.";
            } else if (!data.email.length || !data.email.match(STATE.regEx.email)) {
                errMsg = `Please provide your user with a valid email address. You entered "${data.email}".`;
            }
        }

        if (type === "create") {
            if (data['new-password'] !== data['confirm-new-password']) {
                errMsg = "Passwords must match.";
            } else if (data['new-password'].length < 5) {
                errMsg = "Password must be at least 5 characters.";
            }
        }

        return errMsg || null;
    }
</script>

<?php include_once __DIR__ . "/../../partials/footer.php"; ?>