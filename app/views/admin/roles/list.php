<?php include_once __DIR__ . "/../../partials/header.php"; ?>
<?php include_once __DIR__ . "/../../partials/navbar.php"; ?>

<main>
    <div class="table-wrapper">
        <button class="create-btn continue-btn open-modal-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <line x1="19" x2="19" y1="8" y2="14" />
                <line x1="22" x2="16" y1="11" y2="11" />
            </svg>
            <span>Create Role</span>
        </button>
        <table id="roles-table">
            <thead>
                <tr>
                    <th>Id #</th>
                    <th>Name</th>
                    <th>Created</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $r) {
                    $created_at = new DateTime($r['created_at']);
                    $updated_at = new DateTime($r['updated_at']);
                    $created_at = $created_at->format('M j, Y \@ g:i A T');
                    $updated_at = $updated_at->format('M j, Y \@ g:i A T');
                ?>
                    <tr data-id="<?php echo $r['role_id']; ?>">
                        <td><?php echo $r['role_id']; ?></td>
                        <td><?php echo $r['role_name']; ?></td>
                        <td class="dt-type-date"><?php echo $created_at; ?></td>
                        <td class="dt-type-date"><?php echo $updated_at; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<div id="create-role-modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-options">
                    <span class="modal-close">×</span>
                </div>
                <h1>Adding Role</h1>
            </div>
            <div class="modal-body">
                <form id="create-role-form">
                    <div class="input-container">
                        <label for="role_name">Name</label>
                        <input type="text" name="role_name" placeholder="New Role" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button form="create-role-form" type="submit" class="continue-btn disabled">Submit</button>
            </div>
        </div>
    </div>
</div>

<div id="edit-role-modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-options">
                    <span class="modal-close">×</span>
                </div>
                <h1>Editing Role</h1>
            </div>
            <div class="modal-body">
                <form id="edit-role-form">
                    <div class="input-container">
                        <label>Id #</label>
                        <span id="edit-role-id"></span>
                    </div>
                    <hr style="border: solid 0.5px #d3d3d3;margin: 24px 0;">
                    <div class="input-container">
                        <label for="role_name">Name</label>
                        <input type="text" name="role_name" placeholder="Current Role" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="continue-btn cancel">Cancel</button>
                <button form="edit-role-form" type="submit" class="continue-btn">Update</button>
            </div>
            <div class="modal-footer" style="margin-top: 18px;">
                <button class="continue-btn danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        new DataTable("#roles-table", {
            ...STATE.dtDefaultOpts,
        });

        $(".create-btn").on("click", () => $("#create-role-modal").addClass("showing"));

        $("#create-role-modal input").on('input', () => checkFormIsValid());

        $('button[form="edit-role-form"]').on("click", function(e) {
            e.preventDefault();

            const form = $("#edit-role-form");
            const data = form.serializeObject();
            data.role_id = $("#edit-role-id").text();

            if (!data.role_name.length || !form.find('input[name="role_name"]')[0].checkValidity()) {
                return form.find('input[name="role_name"]')[0].reportValidity();
            }

            $.ajax({
                url: `/roles/${data.role_id}`,
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

        $('button[form="create-role-form"]').on("click", function(e) {
            e.preventDefault();

            const form = $("#create-role-form");
            const data = form.serializeObject();

            if (!data.role_name.length || !form.find('input[name="role_name"]')[0].checkValidity()) {
                return form.find('input[name="role_name"]')[0].reportValidity();
            }

            if (!checkFormIsValid()) return;

            $.ajax({
                url: "/roles",
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

        function checkFormIsValid() {
            const data = $("#create-role-form").serializeObject();
            let disableTheBtn = false;
            for (const key in data) {
                if (Object.prototype.hasOwnProperty.call(data, key)) {
                    // check to see if any value has been at all for inputs
                    if (!data[key].length) disableTheBtn = true;
                }
            }

            $('button[form="create-role-form"]').toggleClass('disabled', disableTheBtn);

            return !disableTheBtn;
        }

        $("#roles-table tbody tr").on("click", function() {
            const modal = $("#edit-role-modal");
            const roleId = $(this).find('td').eq(0).text();
            const roleName = $(this).find('td').eq(1).text();

            modal.find('#edit-role-id').text(roleId);
            modal.find('input[name="role_name"]').val(roleName);

            modal.addClass("showing");
        });

        $("#edit-role-modal button.cancel").on("click", function() {
            $(this).closest('.modal').removeClass('showing');
        });

        $("#edit-role-modal button.danger").on("click", async function() {
            const form = $("#edit-role-form");
            const data = form.serializeObject();
            data.role_id = $("#edit-role-id").text();

            const res = await Swal.fire({
                icon: "warning",
                title: `Deleting "${data.role_name}"`,
                text: "Are you sure that you would like to delete this role?",
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: 'No'
            });

            if (!res.isConfirmed) return;

            $.ajax({
                url: `/roles/${data.role_id}`,
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
</script>

<?php include_once __DIR__ . "/../../partials/footer.php"; ?>