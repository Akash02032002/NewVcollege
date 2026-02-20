/* College Module - JavaScript */

// ---- Image Preview on Registration Form ----
function previewImage(input) {
    const preview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'inline-block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

// ---- Live Search for College List Table ----
function initCollegeSearch() {
    const searchInput = document.getElementById('clgSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#clgTableBody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            if (row.classList.contains('empty-row')) return;
            const text = row.textContent.toLowerCase();
            const match = text.includes(query);
            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        // Show/hide empty state
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    });
}

// ---- View College Detail Modal ----
function viewCollege(id) {
    fetch('index.php?action=view_college&id=' + id)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const c = data.college;
                let imgHtml = '';
                if (c.college_image) {
                    imgHtml = '<img src="../uploads/colleges/' + escHtml(c.college_image) + '" class="modal-clg-img" alt="">';
                }
                document.getElementById('clgDetailContent').innerHTML =
                    imgHtml +
                    detailRow('College Name', escHtml(c.college_name)) +
                    detailRow('Contact', escHtml(c.contact)) +
                    detailRow('State', escHtml(c.state)) +
                    detailRow('City', escHtml(c.city)) +
                    detailRow('Courses', courseBadges(c.courses)) +
                    detailRow('Added On', formatDate(c.created_at));

                new bootstrap.Modal(document.getElementById('viewCollegeModal')).show();
            } else {
                alert('College not found.');
            }
        })
        .catch(() => alert('Error loading college details.'));
}

function detailRow(label, value) {
    return '<div class="clg-detail-row"><div class="clg-detail-label">' + label + '</div><div class="clg-detail-value">' + value + '</div></div>';
}

function courseBadges(courses) {
    if (!courses) return '-';
    return courses.split(',').map(c =>
        '<span class="course-badge">' + escHtml(c.trim()) + '</span>'
    ).join(' ');
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
}

function escHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// ---- Init on DOM Ready ----
document.addEventListener('DOMContentLoaded', function () {
    initCollegeSearch();
});
