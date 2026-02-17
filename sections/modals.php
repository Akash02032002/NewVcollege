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
      action="https://topcolleges.co.in/contact/submit"
      method="POST"
      class="form-horizontal"
      id="contactForm"
    >
      <input
        type="hidden"
        name="_token"
        value="iAG5zTQsXRKqZoZZWWmbdmzySweC1fx2Ze2BqD1W"
        autocomplete="off"
      />
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
