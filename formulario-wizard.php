<?php
/**
 * Plugin Name: Formulário Wizard Modal
 * Description: Adiciona um shortcode [formulario_wizard_modal] que cria um modal oculto, abrível por qualquer botão nativo.
 * Version: 1.4
 * Author: Gabriel Souza do Carmo
 * Text Domain: formulario-wizard-modal
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) exit;

add_action('plugins_loaded', function () {
  load_plugin_textdomain('formulario-wizard-modal', false, dirname(plugin_basename(__FILE__)) . '/languages');
});
/**
 * Carrega o domínio de tradução (Polylang/GlotPress usam os .po/.mo do plugin)
 */
add_action('plugins_loaded', function () {
  load_plugin_textdomain('formulario-wizard-modal', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

// --- Shortcode ---
function formulario_wizard_modal_shortcode() {
    ob_start();
    ?>
    <div id="formWizardModal" class="form-modal" role="dialog" aria-modal="true" aria-labelledby="fwm-title" aria-hidden="true">
      <div class="form-modal-content">
        <button type="button" class="form-modal-close" aria-label="<?php echo esc_attr__('Fechar', 'formulario-wizard-modal'); ?>">&times;</button>

        <div class="divTitle">
            <span id="fwm-title" class="textTitle">
              <?php echo esc_html__('Vamos ajudar você a identificar os termos DeCS/MeSH em seu texto.', 'formulario-wizard-modal'); ?>
            </span><br>
            <!-- <span class="textSub"><?php // echo esc_html__('Preencha os campos abaixo', 'formulario-wizard-modal'); ?></span> -->
        </div>

        <form id="multiStepForm" action="https://decsfinder.bvsalud.org/" method="POST" novalidate>

          <!-- Etapa 1 -->
          <div class="form-step active">
            <label for="fwm-inputText"><?php echo esc_html__('Coloque seu texto aqui:', 'formulario-wizard-modal'); ?></label>
            <textarea id="fwm-inputText" name="inputText" rows="12" aria-required="true"></textarea>

            <button type="button" class="next-step">
              <?php echo esc_html__('Próximo', 'formulario-wizard-modal'); ?>
            </button>
          </div>

          <!-- Etapa 2 -->
          <div class="form-step">
            <label for="inputLang"><?php echo esc_html__('Qual o idioma do texto?', 'formulario-wizard-modal'); ?></label>
            <select name="inputLang" id="inputLang">
              <option value=""><?php echo esc_html__('Não sei', 'formulario-wizard-modal'); ?></option>
              <option value="fr"><?php echo esc_html__('Francês', 'formulario-wizard-modal'); ?></option>
              <option value="pt"><?php echo esc_html__('Português', 'formulario-wizard-modal'); ?></option>
              <option value="es"><?php echo esc_html__('Espanhol', 'formulario-wizard-modal'); ?></option>
              <option value="en"><?php echo esc_html__('Inglês', 'formulario-wizard-modal'); ?></option>
            </select>
          <!--
            <label for="lang"><?php echo esc_html__('Interface:', 'formulario-wizard-modal'); ?></label>
            <select name="lang" id="lang">
              <option value=""><?php echo esc_html__('Não sei', 'formulario-wizard-modal'); ?></option>
              <option value="fr"><?php echo esc_html__('Francês', 'formulario-wizard-modal'); ?></option>
              <option value="pt"><?php echo esc_html__('Português', 'formulario-wizard-modal'); ?></option>
              <option value="es"><?php echo esc_html__('Espanhol', 'formulario-wizard-modal'); ?></option>
              <option value="en"><?php echo esc_html__('Inglês', 'formulario-wizard-modal'); ?></option>
            </select>
            --->
            <input type="hidden" value="<?=pll_current_language();?>" name="lang" id="lang">


            <button type="button" class="prev-step">
              <?php echo esc_html__('Voltar', 'formulario-wizard-modal'); ?>
            </button>
            <button type="button" class="next-step">
              <?php echo esc_html__('Próximo', 'formulario-wizard-modal'); ?>
            </button>
          </div>

          <!-- Etapa 3 -->
          <div class="form-step">
            <label for="outLang"><?php echo esc_html__('Em qual idioma quer os descritores?', 'formulario-wizard-modal'); ?></label>
            <select name="outLang" id="outLang">
              <option value=""><?php echo esc_html__('Não sei', 'formulario-wizard-modal'); ?></option>
              <option value="fr"><?php echo esc_html__('Francês', 'formulario-wizard-modal'); ?></option>
              <option value="pt"><?php echo esc_html__('Português', 'formulario-wizard-modal'); ?></option>
              <option value="es"><?php echo esc_html__('Espanhol', 'formulario-wizard-modal'); ?></option>
              <option value="en"><?php echo esc_html__('Inglês', 'formulario-wizard-modal'); ?></option>
            </select>

            <button type="button" class="prev-step">
              <?php echo esc_html__('Voltar', 'formulario-wizard-modal'); ?>
            </button>
            <button type="submit" id="enviarFormulario" class="submit-step">
              <?php echo esc_html__('Enviar', 'formulario-wizard-modal'); ?>
            </button>
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
  display: none;  position: fixed;
  z-index: 9999;  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
  justify-content: center;  align-items: center;
}
.form-modal-content {
  background: #fff;
  padding: 25px;  width: 90%;
  max-width: 450px;
  border-radius: 10px;
  position: relative;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
  border: 4px solid #b8e22d;
}
.form-modal-close {
  position: absolute;
  top: 10px;  right: 15px;  font-size: 24px;
  cursor: pointer;  background: transparent;
  border: 0;  line-height: 1;
  color: #426a5a;
  padding: 0px;
}
.next-step, .prev-step, .submit-step{
  background-color: #426a5a;
  color: #fff;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  cursor: pointer; transition: 0.4s;
}
.next-step:hover, .prev-step:hover, .submit-step:hover{
  background-color: #b8e22d !important;
  color: #426a5a;
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
  const form  = document.getElementById('multiStepForm');
  if (!modal || !form) return;

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
    modal.setAttribute('aria-hidden', 'false');
    showStep(0);
  };
  window.fecharFormWizard = () => {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
  };
  const closeBtn = document.querySelector('.form-modal-close');
  if (closeBtn) closeBtn.addEventListener('click', fecharFormWizard);
  window.addEventListener('click', e => { if (e.target === modal) fecharFormWizard(); });
  window.addEventListener('keydown', e => { if (e.key === 'Escape') fecharFormWizard(); });

  // Envio garantindo todos os campos visíveis
  form.addEventListener('submit', () => {
    steps.forEach(step => step.classList.add('active'));
  });
});
</script>
<?php }
add_action('wp_footer', 'formulario_wizard_modal_js', 100);

/**
 * Dicas:
 * - Gere o arquivo POT: wp i18n make-pot . languages/formulario-wizard-modal.pot
 * - Para strings de opções dinâmicas (salvas no banco), registre-as no Polylang com pll_register_string() e recupere com pll__().
 */
