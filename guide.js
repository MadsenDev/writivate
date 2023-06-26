document.getElementById("printContent").addEventListener("click", function () {
    const printWindow = window.open("", "_blank");
    const content = document.querySelector(".content").cloneNode(true);
    const hiddenElements = content.querySelectorAll(".hide-on-print");

    hiddenElements.forEach(element => {
        element.style.display = "none";
    });

    const printCSS = `
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                line-height: 1.5;
            }
            h1, h2, h3, h4, h5, h6 {
                font-weight: bold;
            }
            h1 {
                font-size: 24px;
                margin-bottom: 20px;
            }
            h2 {
                font-size: 20px;
                margin-bottom: 16px;
            }
            h3 {
                font-size: 18px;
                margin-bottom: 12px;
            }
            p {
                margin-bottom: 16px;
            }
            ul, ol {
                margin-bottom: 16px;
                padding-left: 20px;
            }
            li {
                margin-bottom: 8px;
            }
            pre {
                background-color: #f5f5f5;
                border: 1px solid #ddd;
                border-radius: 3px;
                font-family: Consolas, monospace;
                font-size: 14px;
                line-height: 1.5;
                overflow: auto;
                padding: 10px;
            }
            code {
                font-family: Consolas, monospace;
                font-size: 14px;
            }
        </style>
    `;

    printWindow.document.write("<html><head><title>Print Content</title>");
    printWindow.document.write(printCSS);
    printWindow.document.write("</head><body>");
    printWindow.document.write(content.innerHTML);
    printWindow.document.write("</body></html>");
    printWindow.document.close();
    printWindow.print();

    printWindow.addEventListener("afterprint", function() {
        printWindow.close();
    });
});

document.getElementById("language-select").addEventListener("change", function () {
    const language = this.value;
    const urlParams = new URLSearchParams(window.location.search);
    if (language) {
        urlParams.set("language", language);
    } else {
        urlParams.delete("language");
    }
    window.location.search = urlParams.toString();
});