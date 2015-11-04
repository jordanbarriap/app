<?php
/**
 * Contiene funciones generales usadas por otros archivos PHP
 *
 * LICENSE: código fuente distribuido con licencia LGPL
 *
 * @author  Cristian Miranda - Kelluwen
 * @copyleft Kelluwen, Universidad Austral de Chile
 * @license www.kelluwen.cl/app/licencia_kelluwen.txt
 * @version 0.1
 *
 * */

/*
 * Esta función es llamada desde rec_form_finalizar_actividad.php y despliega el formulario
 * para el ingreso de recomendación y evaluación al finalizar una actividad.
 * Parametro:
 *      $_datos_anteriores: Contiene la recomendacion y el valor de la evaluacion
 *                          ingresados la primera vez que se finalizo una actividad.
 *                          Si no existen datos, su valor es NULL.
 */
function recDespliegaFormularioRecEvActividad($_datos_anteriores){
    echo "<form id=\"rec_form_recomienda\" name=\"rec_form_recomienda\" action=\"\">";
    echo    "<br />";
    echo    "<span class=\"rec_resaltado\">".$lang_rec_function_resultado_act."</span>";
    echo    "<div class=\"rec_chkbox_evaluacion\">";
        if($_datos_anteriores == null){   //si no hay recomendacion anterior
            echo    "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_bien." <br />";
            echo    "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_bien." <br />";
            echo    "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_mal." <br />";
            echo    "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_mal;
        }
        else{
            if($_datos_anteriores[0]["evaluacion"] == "Muy Bien"){
                echo "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\" checked=\"true\"/>".$lang_rec_function_muy_bien." <br />";
                echo "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_bien." <br />";
                echo "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_mal." <br />";
                echo "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_mal;
            }
            else{
                if($_datos_anteriores[0]["evaluacion"] == "Bien"){
                    echo "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_bien." <br />";
                    echo "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\" checked=\"true\"/>".$lang_rec_function_bien." <br />";
                    echo "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_mal." <br />";
                    echo "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_mal;
                }
                else{
                    if($_datos_anteriores[0]["evaluacion"] == "Mal"){
                        echo "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_bien." <br />";
                        echo "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_bien." <br />";
                        echo "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\" checked=\"true\"/>".$lang_rec_function_mal." <br />";
                        echo "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_mal;
                    }
                    else{
                        if($_datos_anteriores[0]["evaluacion"] == "Muy Mal"){
                            echo "<input type=\"radio\" value=\"Muy Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_muy_bien." <br />";
                            echo "<input type=\"radio\" value=\"Bien\" name=\"rec_chkbox\"/>".$lang_rec_function_bien." <br />";
                            echo "<input type=\"radio\" value=\"Mal\" name=\"rec_chkbox\"/>".$lang_rec_function_mal." <br />";
                            echo "<input type=\"radio\" value=\"Muy Mal\" name=\"rec_chkbox\" checked=\"true\"/>".$lang_rec_function_muy_mal;
                        }
                    }
                }
            }
        }
        echo "</div>";
        echo "<div class=\"rec_chkbox_error\">";
            echo "<label for=\"rec_chkbox\" class=\"error\" style=\"display:none;\">".$lang_rec_function_selecciona_alternativa."</label>";
        echo "</div>";
        echo "<p></p>";
        echo "<span class=\"rec_resaltado\">".$lang_rec_function_deja_reco_pares."</span>";
        echo "<div class=\"rec_caja_nueva_reco\">";
        if($_datos_anteriores == null){
            echo "<textarea id=\"rec_txt_nueva_reco_id\" name=\"rec_txt_nueva_reco\"></textarea>";
        }
        else{
            echo "<textarea id=\"rec_txt_nueva_reco_id\" name=\"rec_txt_nueva_reco\">".$_datos_anteriores[0]["mensaje"]."</textarea>";            
        }
        echo "</div>";
        echo "<div class=\"rec_caracteres_disp\">";
                echo "<span id=\"n_cara_restantes\">1024</span> ".$lang_rec_function_caracteres_disponibles;
        echo "</div>";
    echo "</form>";
}

