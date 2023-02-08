
// This Function Will Upload File to PHP Server
async function UpLoadFile(ID, Email, Pass) {
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.click();

    fileInput.onchange = async () => {
        document.getElementById("loading").style.visibility = "visible";
        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append('File', file);
        formData.append('ID', ID);
        formData.append('Email', Email);
        formData.append('Pass', Pass);

        const response = await fetch('FileUpload.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.text();

        console.log(result)

        if (result == "uploaded") {
            location.reload();
        }
        else {
            alert(result);
        }
    };
}

// This Function Will Delete File from the Server
async function DeleteFile(ID, Email, Pass) {
    const formData = new FormData();
    formData.append('ID', ID);
    formData.append('Email', Email);
    formData.append('Pass', Pass);

    const response = await fetch('DeleteFile.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.text();

    console.log(result)
    if (result == "uploaded") {
        location.reload();
    }
    else {
        alert(result);
    }
}


function downloadURI(dataurl, filename) {
    const link = document.createElement("a");
    link.href = dataurl;
    link.download = filename;
    link.click();
}


function copyLink(datauri) {
    navigator.clipboard.writeText(datauri);
    window.alert("Copied the Shared Link");
}
