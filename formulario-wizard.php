<?php
/**
 * Plugin Name: Formulário Wizard Modal
 * Description: Adiciona um shortcode [formulario_wizard_modal] que cria um modal oculto, abrível por qualquer botão nativo.
 * Version: 1.0
 * Author: Gabriel Souza do Carmo
 */

if (!defined('ABSPATH')) exit;

// --- Shortcode: gera o modal, sem botão ---
function formulario_wizard_modal_shortcode() {
    ob_start();
    ?>
    <div id="formWizardModal" class="form-modal">
      <div class="form-modal-content">
        <span class="form-modal-close">&times;</span>

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
      </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('formulario_wizard_modal', 'formulario_wizard_modal_shortcode');

// --- CSS ---
function formulario_wizard_modal_css() {
    ?>
    <style>
    .form-step { display: none; }
    .form-step.active { display: block; }
    #multiStepForm input, #multiStepForm textarea, #multiStepForm button {
      display: block; width: 100%; margin: 8px 0;
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
    }
    .form-modal-close {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 24px;
      cursor: pointer;
    }
    </style>
    <?php
}
add_action('wp_head', 'formulario_wizard_modal_css');

// --- JS ---
function formulario_wizard_modal_js() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('formWizardModal');
      const btnFechar = document.querySelector('.form-modal-close');

      // Funções públicas: abrir/fechar modal
      window.abrirFormWizard = () => modal.style.display = 'flex';
      window.fecharFormWizard = () => modal.style.display = 'none';

      btnFechar.addEventListener('click', fecharFormWizard);
      window.addEventListener('click', (e) => {
        if (e.target === modal) fecharFormWizard();
      });

      // Wizard
      const steps = document.querySelectorAll('.form-step');
      let currentStep = 0;

      function showStep(stepIndex) {
        steps.forEach((step, i) => step.classList.toggle('active', i === stepIndex));
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

        const response = await fetch(urlDestino, { method: 'POST', body: data });
        if (response.ok) {
          alert('Formulário enviado com sucesso!');
          fecharFormWizard();
        } else {
          alert('Erro ao enviar. Tente novamente.');
        }
      });
    });
    </script>
    <?php
}
add_action('wp_footer', 'formulario_wizard_modal_js', 100);
