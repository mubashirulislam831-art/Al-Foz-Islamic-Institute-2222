  const triggerSubmitScript = `
      try {
          const formData = new FormData(form);
          const response = await fetch(form.action, {
              method: 'POST',
              body: formData,
              headers: {
                  'Accept': 'application/json'
              }
          });
          
          let result = {};
          try {
              result = await response.json();
          } catch(e) {}
          
          if (typeof window.showSuccessAnimation === 'function') {
              window.showSuccessAnimation();
          }
          
          setTimeout(() => {
              if (result && result.redirect) {
                  window.location.href = result.redirect;
              } else {
                  // Fallback
                  const isEdit = form.action.includes('edit_');
                  const isTeacher = form.action.includes('teacher');
                  const entity = isTeacher ? 'teacher_profile.php' : 'student_profile.php';
                  window.location.href = entity;
              }
          }, 1500);
      } catch (err) {
          alert('Network error occurred.');
          if (btn) {
              btn.innerHTML = 'Retry';
              btn.disabled = false;
          }
      }
`;
