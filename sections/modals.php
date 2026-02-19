<!-- ========================================
     MODALS SECTION - Application Form
     ======================================== -->
<div
  class="modal fade"
  id="contactModal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="contactModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <form
      action="/College/NewVcollege/submit_application.php"
      method="POST"
      class="form-horizontal"
      id="contactForm"
    >
      <input type="hidden" name="_token" value="" autocomplete="off" />
      <div class="modal-content">
        <div class="modal-header">
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="contactModalLabel">
            Apply for Admission
          </h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="college_id" id="modalCollegeId" />
          <input type="hidden" name="college_name" id="modalCollegeName" />

          <div class="form-group">
            <label class="col-sm-3 control-label">Full Name</label>
            <div class="col-sm-9">
              <input
                type="text"
                name="name"
                id="applicantName"
                class="form-control"
                placeholder="Enter Name"
                required=""
                value=""
              />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Email</label>
            <div class="col-sm-9">
              <input
                type="email"
                name="email"
                id="applicantEmail"
                class="form-control"
                placeholder="Enter Email"
                required=""
                value=""
              />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Phone</label>
            <div class="col-sm-9">
              <input
                type="tel"
                name="phone"
                id="applicantPhone"
                class="form-control"
                placeholder="Enter Phone Number"
                required=""
                value=""
              />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Course</label>
            <div class="col-sm-9">
              <input
                type="text"
                name="course_interest"
                id="applicantCourse"
                class="form-control"
                placeholder="Enter Course"
                required
                value=""
              />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">State</label>
            <div class="col-sm-9">
              <input
                type="text"
                name="state"
                id="applicantState"
                class="form-control"
                placeholder="Enter state"
                required
                value=""
              />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Region (optional)</label>
            <div class="col-sm-9">
              <input type="text" name="region" id="applicantRegion" class="form-control" placeholder="Enter region" value="" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">District (optional)</label>
            <div class="col-sm-9">
              <input type="text" name="district" id="applicantDistrict" class="form-control" placeholder="Enter district" value="" />
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-3 control-label">Message</label>
            <div class="col-sm-9">
              <textarea
                name="message"
                id="applicantMessage"
                class="form-control"
                rows="4"
                placeholder="Tell us more about your interest..."
              ></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="submit"
            class="btn btn-primary btn-lg"
            id="submitBtn"
          >
            Submit Application
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
