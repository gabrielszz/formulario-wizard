<?php
/**
 * Plugin Name: Formulário Wizard Modal
 * Description: Adiciona um shortcode [formulario_wizard_modal] que cria um modal oculto, abrível por qualquer botão nativo.
 * Version: 1.2
 * Author: Gabriel Souza do Carmo
 */

if (!defined('ABSPATH')) exit;

// --- Shortcode ---
function formulario_wizard_modal_shortcode() {
    ob_start();
    ?>
    <div id="formWizardModal" class="form-modal">
      <div class="form-modal-content">
        
        <span class="form-modal-close">&times;</span>
        <div class="divTitle">
            <span class="textTitle">Vamos ajudar você identificar os termos DeCS/MeSH em seu texto.</span><BR>
            <!--<span class="textSub">Preencha os campos abaixo</span>-->
        </div>
        <form id="multiStepForm" action="https://decsfinder.bvsalud.org/dmf" method="POST" novalidate>
          
          <!-- Etapa 1 -->
          <div class="form-step active">

          
            <label>Coloque seu texto aqui:</label>
            <textarea name="inputText" rows="12"></textarea>

            <button type="button" class="next-step">Próximo</button>
          </div>

          <!-- Etapa 2 -->
          <div class="form-step">

            <label>Qual o idioma do texto?</label>
            <select name="inputLang" id="inputLang">
              <option value="">Não sei</option>
              <option value="fr">Francês</option>
              <option value="pt">Português</option>
              <option value="es">Espanhol</option>
              <option value="en">Inglês</option>
            </select>





            <label>Interface:</label>
            <select name="lang" id="lang">
              <option value="">Não sei</option>
              <option value="fr">Francês</option>
              <option value="pt">Português</option>
              <option value="es">Espanhol</option>
              <option value="en">Inglês</option>
            </select>

    

            <button type="button" class="prev-step">Voltar</button>
            <button type="button" class="next-step">Próximo</button>
          </div>

          <!-- Etapa 3 -->
          <div class="form-step">

            <label>Em qual idioma quer os descritores ?</label>
            <select name="outLang" id="outLang">
              <option value="">Não sei</option>
              <option value="fr">Francês</option>
              <option value="pt">Português</option>
              <option value="es">Espanhol</option>
              <option value="en">Inglês</option>
            </select>

            <button type="button" class="prev-step">Voltar</button>
            <button type="submit" id="enviarFormulario">Enviar</button>
          </div>

        </form>
      </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('formulario_wizard_modal', 'formulario_wizard_modal_shortcode');

// --- CSS ---
function formulario_wizard_modal_css() { ?>
<style>
.form-step {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  visibility: hidden;
  opacity: 0;
  transition: opacity 0.3s ease;
}
.form-step.active {
  position: relative;
  visibility: visible;
  opacity: 1;
}
#multiStepForm input, 
#multiStepForm textarea, 
#multiStepForm button, 
#multiStepForm select {
  display: block; 
  width: 100%; 
  margin: 8px 0;
}

/* Modal oculto */
.form-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
  justify-content: center;
  align-items: center;
}
.form-modal-content {
  background: #fff;
  padding: 25px;
  width: 90%;
  max-width: 450px;
  border-radius: 10px;
  position: relative;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    border: 4px solid #b8e22d;
}
.form-modal-close {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 24px;
  cursor: pointer;
}
.next-step, .prev-step{
  background-color: #426a5a;
  color: #fff;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  cursor: pointer;
}
.divTitle{
    margin-bottom:22px;
}
.textTitle{
    color: #426a5a; font-size: 20px; font-weight: bold;
}
.textSub{
    font-size: 12px; font-weight: bold;
}
</style>
<?php }
add_action('wp_head', 'formulario_wizard_modal_css');

// --- JS ---
function formulario_wizard_modal_js() { ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('formWizardModal');
  const form = document.getElementById('multiStepForm');
  const steps = form.querySelectorAll('.form-step');
  let currentStep = 0;

  const showStep = (i) => {
    steps.forEach((s, idx) => s.classList.toggle('active', idx === i));
  };

  document.querySelectorAll('.next-step').forEach(btn => {
    btn.addEventListener('click', () => {
      if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
      }
    });
  });

  document.querySelectorAll('.prev-step').forEach(btn => {
    btn.addEventListener('click', () => {
      if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
      }
    });
  });

  // Modal abrir/fechar
  window.abrirFormWizard = () => {
    modal.style.display = 'flex';
    showStep(0);
  };
  window.fecharFormWizard = () => modal.style.display = 'none';
  document.querySelector('.form-modal-close').addEventListener('click', fecharFormWizard);
  window.addEventListener('click', e => { if (e.target === modal) fecharFormWizard(); });

  // Envio sem erro de foco e com todos os dados
  form.addEventListener('submit', () => {
    steps.forEach(step => step.classList.add('active')); // garante que todos os campos estejam visíveis no envio
  });
});
</script>
<?php }
add_action('wp_footer', 'formulario_wizard_modal_js', 100);
