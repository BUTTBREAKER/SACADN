<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro año y seccion</title>  
    <link rel="stylesheet" type="text/css" href="">
</head>
<style type="text/css">
  .contenedor
{ 
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 0;
  padding: 10px 5px 10px 5px;
  width: 700px;
  box-shadow: 10px 10px 10px 10px rgba(0,0,0,0.1);
  border: 1px solid ;
  margin: 0 auto;

}
.header{
  height: 30px;
}
 .title
 {   
     justify-content: center;
     width: 300px;
     height: 1px;
     padding:0px 1px 0px 1px;
     position: relative;
     color: black;
     font-size: 20px;
   }
.title1{
    display: flex;
    margin-block-start: none;
    margin-block-end: 11px;
    margin-inline-start:10px;
    margin-inline-end: 10px;
    font-weight: none;
}
.formulario 
  {
    width: 640px;
    margin: 0 auto;
    padding:20px 15px 20px 15px;
    background-color:#E7F7EC;
    border: 4px solid ;
    border-color: #2DA0FB;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    align-items: 30px;

  }
      .formulario input[type="number-per"]{
        width: 150px;
        padding: 3px 3px;
         }
  {
    width: 20px;
    padding:  20px 30px;
    margin: 5px 0;
    border: 5px solid #72D6EE;
    border-radius: 5px;
    font-size: xx-large;
  }

  .formulario input [type="sudmit"]
  {
    padding: 20px 15px 20px 15px;
    margin-top: 20px;
    background-color: #A1CFFF;
    color: white;
    border: none;
    border-radius: 30px; 
    cursor: pointer;
    transition: background-color 0.3s ease;
    

.formulario input[type="submit"]:hover
{
    background-color: #72D6EE;
    border: 1px solid #2DA0FA
}

.contenedor1 select {
            width:35%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 10px;
                      
.momento
  {
    padding: 15px;
    margin-top: 20px;
    color: white;
    border: none;
    border-radius: 30px; 
    background-color: #72D6EE;
    border: 1px solid #2DA0FA;
    cursor: pointer;
    padding-inline-end: 10px;
    transition: background-color 0.3s ease }


}                      
.input-contenedor label{

    position: adsolute;
    top: -10px;
    left: 10px;
    background-color: #E7F7EC;
    padding: 0 5px;
    font-size: x-large;
    
}

.contenedor1 label
{
 position: adsolute;
 top: 20px;
 left: 20px;
 color: black;
 transition: top 0.5s, font-size 0.5s, color 0.5s;
 justify-content: center;
}
 .formulario input:focus+label,
 .formulario input:valid+label
 {
    top: 20px;
    font-size: 20px;
    color: E7F7EC;
  
 }
.representante{
  padding: 2px 3px;
}
.a#o_secc{
  padding: 100px;
  margin-top: 300px;
}
.button  [type="sudmit"]{
  border-radius: 50px 30px 50px 30px;
  border: 100px;
}
</style>
 <body>

  <div class="contenedor">
    <form class="formulario" class="submit-a#o_seccion" method="post">
    
    <div class="input-contenedor">
        <header class="header">
           <nav class="title">
     <h4 class="title1">Registro años y secciones</h4>
           </nav>
        </header>

        <div class="contenedor1">
              <i><svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 14 14"><path fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" d="M13.5 9.54a1 1 0 0 0-1-1h-3v1h-5v-1h-3a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1Zm-12-4h11m-11-3h11"/></svg></i>
              <label for="nom_per">Periodo</label>
          <input type="text" id="per" placeholder="" name="nom_per" required>
               </div>

               <div class="contenedor1">

              <label for="momento">Momento</label>
          <div class="momento">
                    <button class="submit">Momento I</button> 
                    <button class="submit">Momento II</button>
                    <button class="submit">Momento III</button>
                </div>
        <div class="contenedor1">

          <label for="a#os">Años</label>
          <i></i>
               <div class="a#o_secc">
                    <button type="submit">1ero</button> 
                    <button type="submit">2do</button>
                    <button type="submit">3ero</button>
                    <button type="submit">4to</button>
                    <button type="submit">5to</button>
                </div>
               

             <div class="contenedor1">
          <label for="seccion">Secciones</label>
               <div class="a#o_secc">
                    <button type="submit">A</button>
                    <button type="submit">B</button>
                    <button type="submit">C</button>
                    <button type="submit">D</button>
                    <button type="submit">E</button>
                    <button type="submit">F</button>
                    <button type="submit">G</button>
                    <button type="submit">H</button>
                    <button type="submit">I</button>
                    <button type="submit">J</button>
                    <button type="submit">K</button>
                </div>
          <br>
          <div class="form-button">
              <button type="submit">Consultar</button>
              <button type="submit">Cancelar</button>
                </div>
          <br>

              
           </div>
           </form>
           </div>

 </body>
 </html>



