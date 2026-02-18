<!-- ========================================
     MODALS SECTION - Application Form
     ======================================== -->
<div
  class="modal fade"
  id="contactModal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="contactModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form
      action="submit_application.php"
      method="POST"
      class="form-horizontal"
      id="contactForm">
      <div class="modal-content">
        <div class="modal-header">
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
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
                placeholder="Enter Full Name"
                required=""
                value="" />
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
                value="" />
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
                value="" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">State</label>
            <div class="col-sm-9">
              <select
                name="state"
                id="applicantState"
                class="form-control"
                required="">
                <option value="">Select State</option>
                <option value="Andhra Pradesh">Andhra Pradesh</option>
                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                <option value="Assam">Assam</option>
                <option value="Bihar">Bihar</option>
                <option value="Chhattisgarh">Chhattisgarh</option>
                <option value="Goa">Goa</option>
                <option value="Gujarat">Gujarat</option>
                <option value="Haryana">Haryana</option>
                <option value="Himachal Pradesh">Himachal Pradesh</option>
                <option value="Jharkhand">Jharkhand</option>
                <option value="Karnataka">Karnataka</option>
                <option value="Kerala">Kerala</option>
                <option value="Madhya Pradesh">Madhya Pradesh</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Manipur">Manipur</option>
                <option value="Meghalaya">Meghalaya</option>
                <option value="Mizoram">Mizoram</option>
                <option value="Nagaland">Nagaland</option>
                <option value="Odisha">Odisha</option>
                <option value="Punjab">Punjab</option>
                <option value="Rajasthan">Rajasthan</option>
                <option value="Sikkim">Sikkim</option>
                <option value="Tamil Nadu">Tamil Nadu</option>
                <option value="Telangana">Telangana</option>
                <option value="Tripura">Tripura</option>
                <option value="Uttar Pradesh">Uttar Pradesh</option>
                <option value="Uttarakhand">Uttarakhand</option>
                <option value="West Bengal">West Bengal</option>
                <option value="Delhi">Delhi</option>
                <option value="Chandigarh">Chandigarh</option>
                <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                <option value="Ladakh">Ladakh</option>
                <option value="Puducherry">Puducherry</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Course Interest</label>
            <div class="col-sm-9">
              <input
                type="text"
                name="course_interest"
                id="applicantCourse"
                class="form-control"
                placeholder="Course Interested In"
                required=""
                value="" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="submit"
            class="btn btn-primary btn-lg"
            id="submitBtn">
            Submit Application
          </button>
        </div>
      </div>
    </form>
  </div>
</div>