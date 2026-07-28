      try {
          window.onbeforeunload = null;
          const formData = new FormData(form);
          const response = await fetch(form.action || window.location.href, {
              method: 'POST',
              body: formData,
              headers: { 'Accept': 'application/json' }
          });
          let result = {};
          try { result = await response.json(); } catch(e) {}
          if (typeof window.showSuccessAnimation === 'function') window.showSuccessAnimation();
          setTimeout(() => {
              if (result && result.redirect) { window.location.href = result.redirect; }
              else { window.location.reload(); }
          }, 1500);
      } catch (err) {
          alert('Network error occurred.');
          if (btn) { btn.innerHTML = 'Retry'; btn.disabled = false; }
      }
