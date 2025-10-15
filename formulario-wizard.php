<?php
/**
 * Plugin Name: Formulário Wizard Personalizado
 * Description: Adiciona um shortcode [formulario_wizard] que exibe um formulário multi-etapas com envio via POST.
 * Version: 1.0
 * Author: Gabriel Souza do Carmo
 */

if (!defined('ABSPATH')) exit; // segurança básica

// --- Shortcode principal ---
function formulario_wizard_shortcode() {
    ob_start();
    ?>
    <div id="multiStepForm">
      <!-- Etapa 1 -->
      <div class="form-step active">
        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>E-mail:</label>
        <input type="email" name="email" required>

        <button type="button" class="next-step">Próximo</button>
      </div>

      <!-- Etapa 2 -->
      <div class="form-step">
        <label>Empresa:</label>
        <input type="text" name="empresa" required>

        <label>Telefone:</label>
        <input type="text" name="telefone">

        <button type="button" class="prev-step">Voltar</button>
        <button type="button" class="next-step">Próximo</button>
      </div>

      <!-- Etapa 3 -->
      <div class="form-step">
        <label>Mensagem:</label>
        <textarea name="mensagem"></textarea>

        <button type="button" class="prev-step">Voltar</button>
        <button type="submit" id="enviarFormulario">Enviar</button>
      </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('formulario_wizard', 'formulario_wizard_shortcode');

// --- CSS inline ---
function formulario_wizard_css() {
    ?>
    <style>
    .form-step { display: none; }
    .form-step.active { display: block; }
    #multiStepForm input, #multiStepForm textarea, #multiStepForm button {
        display: block;
        width: 100%;
        margin: 8px 0;
    }
    </style>
    <?php
}
add_action('wp_head', 'formulario_wizard_css');

// --- Script JS ---
function formulario_wizard_js() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const steps = document.querySelectorAll('.form-step');
      let currentStep = 0;

      function showStep(stepIndex) {
        steps.forEach((step, i) => {
          step.classList.toggle('active', i === stepIndex);
        });
      }

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

      document.querySelector('#enviarFormulario').addEventListener('click', async () => {
        const form = document.querySelector('#multiStepForm');
        const data = new FormData(form);

        const urlDestino = 'https://seusite.com/recebe-dados.php'; // altere aqui
        const response = await fetch(urlDestino, {
          method: 'POST',
          body: data
        });

        if (response.ok) {
          alert('Formulário enviado com sucesso!');
        } else {
          alert('Erro ao enviar. Tente novamente.');
        }
      });
    });
    </script>
    <?php
}
add_action('wp_footer', 'formulario_wizard_js', 100);
