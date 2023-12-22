<?= loadPartial('head') ?>
<?= loadPartial('navbar') ?>
<?= loadPartial('showcase') ?>
<?= loadPartial('top-banner') ?>

<!-- Edit a Job Form Box -->
<section class="flex justify-center items-center mt-20">
    <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Edit Job Listing</h2>
        <form method="POST" action="/listings/<?= $listing->id ?>">
            <input type="hidden" name="_method" value="PUT">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Job Info
            </h2>
            <?= loadPartial('errors', [
                'errors' => $errors ?? []
            ]) ?>
            <div class="mb-4">
                <input type="text" name="title" placeholder="Job Title"
                    class="w-full px-4 py-2 border rounded focus:outline-none" value="<?= $listings->title ?? '' ?>" />
            </div>
            <div class="mb-4">
                <textarea name="description" placeholder="Job Description"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $listings->description ?? '' ?>"></textarea>
            </div>
            <div class="mb-4">
                <input type="text" name="salary" placeholder="Annual Salary"
                    class="w-full px-4 py-2 border rounded focus:outline-none" value="<?= $listings->salary ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input type="text" name="requirements" placeholder="Requirements"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $listings->requirements ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input type="text" name="benefits" placeholder="Benefits"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $listings->benefits ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input type="text" name="tags" placeholder="Tags"
                    class="w-full px-4 py-2 border rounded focus:outline-none" value="<?= $listings->tags ?? '' ?>" />
            </div>
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Company Info & Location
            </h2>
            <div class="mb-4">
                <input type="text" name="company" placeholder="Company Name"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $listings->company ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input type="text" name="address" placeholder="Address"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $listings->address ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input type="text" name="city" placeholder="City"
                    class="w-full px-4 py-2 border rounded focus:outline-none" value="<?= $listings->city ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input type="text" name="country" placeholder="Country"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $listings->country ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input type="text" name="phone" placeholder="Phone"
                    class="w-full px-4 py-2 border rounded focus:outline-none" value="<?= $listings->phone ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input type="email" name="email" placeholder="Email Address For Applications"
                    class="w-full px-4 py-2 border rounded focus:outline-none" value="<?= $listings->email ?? '' ?>" />
            </div>
            <button class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none">
                Save
            </button>
            <a href="/"
                class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none">
                Cancel
            </a>
        </form>
    </div>
</section>

<?= loadPartial('bottom-banner') ?>
<?= loadPartial('footer') ?>