
    function loadImage(url) {
        return new Promise(resolve => {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', url , true);
            xhr.responseType="blob";
            xhr.onload= function (e){
                const reader = new FileReader();
                reader.onload = function(event){
                    const res = event.target.result;
                    resolve(res);
                }   
                const file = this.response;
                reader.readAsDataURL(file);

            }

            xhr.send();
            
        });
        
    }

    let signaturePad = null;

    window.addEventListener('load', async () => {
    //const canvas = document.querySelector("canvas");
    //canvas.height = canvas.offsetHeight;
    //canvas.width = canvas.offsetWidth;


    // signaturePad = new SignaturePad(canvas, {});

        const form = document.querySelector('#form');
    
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            let nombre= document.getElementById('nombre').value;
            let apellido= document.getElementById('apellido').value;
            let puesto= document.getElementById('puesto').value;
            let unidadAdm= document.getElementById('UAD').value;
            let lugar= document.getElementById('lugar').value;
            let motivo= document.getElementById('motivo').value;
            let asunto= document.getElementById('asunto').value;
            let tarj= document.getElementById('tarj').value;
            let dias= document.getElementById('dias').value;
            let desde= document.getElementById('desde').value;
            let hasta= document.getElementById('hasta').value;


            generatePDF(nombre, apellido, puesto, unidadAdm, lugar, motivo,asunto, dias, desde, hasta, tarj,);
        })
    })


    async function generatePDF(nombre, apellido, puesto, unidadAdm, lugar, motivo,asunto, dias, desde, hasta, tarj,){
        const image = await loadImage("Solicitud.jpg");
        
    
        //const signatureImage = signaturePad.toDataURL();
        const pdf = new jsPDF('p', 'pt', 'letter');


        pdf.addImage(image, 'PNG', 0, 0, 565, 792);
        //pdf.addImage(signatureImage, 'PNG', 10, 615, 300, 100);

        pdf.setFontSize(12);
        pdf.text(asunto, 250, 350, 0, 0);

        const date= new Date();
        const horaActual = date.getHours().toString().padStart(2, '0');
    const minutosActuales = date.getMinutes().toString().padStart(2, '0');
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = String(date.getFullYear());
        pdf.setFontSize(10);
        pdf.text(horaActual + ':' + minutosActuales, 60, 511);
        pdf.text(`${day}/${month}/${year}`, 445, 60);


        pdf.setFontSize(10);
        pdf.text(nombre, 60, 160);
        pdf.text(apellido, 350, 160 );
        pdf.text(puesto, 320, 185);
        pdf.text(unidadAdm, 320, 215);
        pdf.text(lugar, 320, 250);
        pdf.text(motivo, 60, 425);
        pdf.text(tarj, 315, 283);
        pdf.text(dias, 366, 499);
        pdf.text(desde,320, 510);
        pdf.text(hasta,320, 538 );

        pdf.save("solicitud.pdf")

        const pdfData = pdf.output('datauristring');


    // Enviar el PDF al servidor
        
    }


