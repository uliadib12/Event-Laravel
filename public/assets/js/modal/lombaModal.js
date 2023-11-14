function openModal() {
    $("#editModal").fadeIn();
}

function openDelModal() {
    $("#deleteModal").fadeIn();
}

function closeDelModal() {
    $("#editModal").fadeOut();
}

function closeModal() {
    $("#editModal").fadeOut();
}

function openInfoModal(id) {
    $("#infoModal").fadeIn();
    console.log("Opening modal for lomba ID:", id);

    $(document).on("click", outsideModalClick);
}

function outsideModalClick(e) {
    if (!$(e.target).closest(".tr-modal-content").length) {
        closeInfoModal();
    }
}

function closeInfoModal() {
    $("#infoModal").fadeOut();
    clearConsole();
    $(document).off("click", outsideModalClick);
}

function clearConsole() {
    if (window.console && window.console.clear) {
        console.clear();
    }
}

$(document).ready(function () {
    $(document).on("click", ".deletebtn", function () {
        let id = $(this).data("lomba-id");
        // reset
        $("#del_id").html("");
        $("#del_nama_lomba").html("");

        openDelModal();

        $.ajax({
            type: "GET",
            url: "/dashboard/lomba/show/" + id,
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                console.log("Response from server:", response);

                $("#del_id").html(response.lomba.id);
                $("#del_nama_lomba").html(response.lomba.nama_lomba);

                closeDelModal();
            },
        });

        $("#confirmButton").on("click", function () {
            console.log("Deleting event with ID:", id);
            $.ajax({
                type: "DELETE",
                url: "/dashboard/lomba/destroy/" + id,
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    $("#del_id").html(response.lomba.id);
                    $("#del_nama_lomba").html(response.lomba.nama_lomba);

                    // reload page
                    location.reload();

                    closeDelModal();
                },
            });

            $("#confirmButton").off("click");
        });
    });
    $(document).on("click", ".editbtn", function () {
        let id = $(this).data("lomba-id");
        openModal();

        // get modal
        let modal = $("#editModal");
        console.log(modal);

        // get #id from modal
        let idModal = modal.find("#id");
        console.log(idModal);

        $.ajax({
            type: "GET",
            url: "/dashboard/lomba/edit/" + id,
            success: function (response) {
                console.log("Response from server:", response);

                idModal.val(response.lomba.id);
                console.log(idModal.val());
                $("#nama_lomba").val(response.lomba.nama_lomba);
                $("#keterangan").val(response.lomba.keterangan);
                $("#ruangan_lomba").val(response.lomba.ruangan_lomba);
                $("#kuota_lomba").val(response.lomba.kuota_lomba);

                const convertedDateTimeString = response.lomba.pelaksanaan_lomba.replace(" ", "T");
                $("#pelaksanaan_lomba").val(convertedDateTimeString);
            },
        });
    });

    $(document).on("click", ".openInfoModalBtn", function () {
        var id = $(this).data("lomba-id");
        openInfoModal(id);

        $.ajax({
            type: "GET",
            url: "/dashboard/lomba/show/" + id,
            success: function (response) {
                console.log("Response from server:", response);

                $("#id").val(response.lomba.id);
                $("#nama_lomba").val(response.lomba.nama_lomba);
                $("#keterangan").val(response.lomba.keterangan);
                $("#ruangan_lomba").val(response.lomba.ruangan_lomba);
                $("#kuota_lomba").val(response.lomba.kuota_lomba);

                var formattedDate = new Date(response.lomba.pelaksanaan_lomba)
                    .toISOString()
                    .slice(0, 16);
                $("#pelaksanaan_lomba").val(formattedDate);
            },
        });
    });

    $(document).on("click", ".upImagebtn", function () {
        var modal = $("#upImageModal");
        modal.fadeIn();

        var id = $(this).val();
        modal.find("#id").val(id);

        // get banner and poster input from this modal
        var bannerInput = modal.find("#banner");
        var posterInput = modal.find("#poster");

        // get loader class from this modal
        var loader = modal.find(".loader");
        // unhide loader
        loader.show();

        // reset input
        bannerInput.val("");
        posterInput.val("");

        var posterContainer = document.getElementById("poster-container");
        posterContainer.style.display = "none";

        getImage(id, posterContainer, loader);
    });

    function getImage(id, posterContainer, loader) {
        $.ajax({
            type: "GET",
            url: "/dashboard/lomba/show/" + id,
            success: function (response) {
                loader.hide();
                if(response.lomba.poster == null) {
                    posterContainer.style.display = "block";
                    posterContainer.src = "/assets/images/blank.jpg";
                    posterContainer.alt = "Blank Image";
                    posterContainer.style.width = "10em";

                    return;
                }
                posterContainer.style.display = "block";
                posterContainer.src =
                    "/storage/lomba/poster/" + id + "/" + response.lomba.poster;
                posterContainer.alt = "Poster Lomba";
                posterContainer.style.width = "10em";
            },
        });
    }
});
