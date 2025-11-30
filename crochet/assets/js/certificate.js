function generateCertificate() {
    const name = document.getElementById("username").value.trim();

    if (name === "") {
        alert("Please enter your name.");
        return;
    }

    document.getElementById("certName").innerText = name;

    const today = new Date().toLocaleDateString();
    document.getElementById("dateToday").innerText = today;

    document.getElementById("certificate").classList.remove("hide");
    document.getElementById("downloadBtn").classList.remove("hide");
}

async function downloadPDF() {
    const certificate = document.getElementById("certificate");

    const canvas = await html2canvas(certificate, { scale: 2 });
    const imgData = canvas.toDataURL("image/png");

    const pdf = new jspdf.jsPDF("landscape", "pt", "a4");

    const width = pdf.internal.pageSize.getWidth();
    const height = (canvas.height * width) / canvas.width;

    pdf.addImage(imgData, "PNG", 0, 0, width, height);
    pdf.save("Crochet_Certificate.pdf");
}
