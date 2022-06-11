<?php

namespace App\Base\Services\ImageUploader;

use App\Base\Services\Hash\HashGeneratorContract;
use App\Base\Services\ImageEncoder\ImageEncoderContract;
use Exception;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploader implements ImageUploaderContract
{
    /**
     * The ImageEncoder instance.
     *
     * @var \App\Base\Services\ImageEncoder\ImageEncoderContract
     */
    protected $encoder;

    /**
     * The HashGenerator instance.
     *
     * @var \App\Base\Services\Hash\HashGeneratorContract
     */
    protected $hashGenerator;

    /**
     * The config array.
     *
     * @var array
     */
    protected $config;

    /**
     * Image encoding format.
     *
     * @var string
     */
    protected $format;

    /**
     * Image encoding quality.
     *
     * @var int
     */
    protected $quality = 100;

    /**
     * New image width.
     *
     * @var int|null
     */
    protected $resizeWidth = null;

    /**
     * New image height.
     *
     * @var int|null
     */
    protected $resizeHeight = null;

    /**
     * The uploaded file.
     *
     * @var \Illuminate\Http\UploadedFile|null
     */
    protected $file = null;

    /**
     * ImageUploader constructor.
     *
     * @param \App\Base\Services\ImageEncoder\ImageEncoderContract $encoder
     * @param \App\Base\Services\Hash\HashGeneratorContract $hashGenerator
     * @param \Illuminate\Config\Repository $config
     */
    public function __construct(ImageEncoderContract $encoder, HashGeneratorContract $hashGenerator, ConfigRepository $config)
    {
        $this->encoder = $encoder;
        $this->hashGenerator = $hashGenerator;
        $this->config = $config['base'];
        $this->format = $this->config('uploads.image.encode');
    }

    /**
     * Save the user profile picture.
     *
     * @return string Returns the saved filename
     */
    public function saveProfilePicture()
    {
        $this->validateFile();

        $config = $this->config('user.upload.profile-picture');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        $image = $this->encodeImage();

        $filename = $this->hashGenerator->extension($this->format)->make();

        $filePath = file_path(data_get($config, 'path'), $filename);

        Storage::put($filePath, $image);

        return $filename;
    }

    /**
     * Save the user profile picture.
     *
     * @return string Returns the saved filename
     */
    public function saveDriverDocument($driver_id)
    {
        $this->validateFile();

        $config = $this->config('driver.upload.documents');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        $image = $this->encodeImage();

        $filename = $this->hashGenerator->extension($this->format)->make();

        $filePath = file_path(data_get($config, 'path'), $filename, $driver_id);

        Storage::put($filePath, $image);

        return $filename;
    }

    /**
     * Save the user profile picture.
     *
     * @return string Returns the saved filename
     */
    public function saveDriverProfilePicture()
    {
        $this->validateFile();

        $config = $this->config('driver.upload.profile-picture');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        $image = $this->encodeImage();

        $filename = $this->hashGenerator->extension($this->format)->make();

        $filePath = file_path(data_get($config, 'path'), $filename);

        Storage::put($filePath, $image);

        return $filename;
    }

    /**
     * Save the user profile picture.
     *
     * @return string Returns the saved filename
     */
    public function savePushImage()
    {
        $this->validateFile();

        $config = $this->config('pushnotification.upload.images');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        $image = $this->encodeImage();

        $filename = $this->hashGenerator->extension($this->format)->make();

        $filePath = file_path(data_get($config, 'path'), $filename);

        Storage::put($filePath, $image);

        return $filename;
    }
    
    /**
     * Save the user profile picture.
     *
     * @return string Returns the saved filename
     */
    public function saveSystemAdminLogo()
    {
        $this->validateFile();

        $config = $this->config('system-admin.upload.logo');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        // $image = $this->encodeImage();

        // $filename = $this->hashGenerator->extension($this->format)->make();

        // $filePath = file_path(data_get($config, 'path'), $filename);

        // Storage::put($filePath, $image);

		$image = $this->file;
		$filePath = data_get($config, 'path');
		
		$name = Storage::put($filePath, $image);
		$pos = strpos($name,'//');
		$str = substr($name,$pos+2);

		return $str;
    }

    /**
     * Save the VehicleType Image.
     *
     * @return string Returns the saved filename
     */
    public function saveVehicleTypeImage()
    {
        $this->validateFile();

        $config = $this->config('types.upload.images');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        $image = $this->encodeImage();

        $filename = $this->hashGenerator->extension($this->format)->make();

        $filePath = file_path(data_get($config, 'path'), $filename);

        Storage::put($filePath, $image);

        return $filename;
    }

    /**
     * Save the VehicleType Image.
     *
     * @return string Returns the saved filename
     */
    public function saveCompanyImage($admin_id)
    {
        $this->validateFile();

        $config = $this->config('company.upload.images');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        $image = $this->encodeImage();

        $filename = $this->hashGenerator->extension($this->format)->make();

        $filePath = file_path(data_get($config, 'path'), $filename, $admin_id);

        Storage::put($filePath, $image);

        return $filename;
    }

    public function saveOwnerDocument($ownerId)
    {
        $this->validateFile();

        $config = $this->config('owner.upload.documents');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        $image = $this->encodeImage();

        $filename = $this->hashGenerator->extension($this->format)->make();

        $filePath = file_path(data_get($config, 'path'), $filename, $ownerId);

        Storage::put($filePath, $image);

        return $filename;
    }



    /**
     * Set the uploaded image file to manipulate.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return $this
     */
    public function file(UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Set the new image size values.
     *
     * @param int|null $width
     * @param int|null $height
     * @return $this
     */
    public function resize($width = null, $height = null)
    {
        $this->resizeWidth = $width;
        $this->resizeHeight = $height;

        return $this;
    }

    /**
     * Set the encoding quality.
     * Value can be between 10 - 100.
     *
     * @param int $quality
     * @return $this
     */
    public function quality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * Set the image encoding format.
     *
     * @param string $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get the config value.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    protected function config($key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Check if the file is set.
     *
     * @throws Exception
     */
    protected function validateFile()
    {
        if (is_null($this->file)) {
            throw new Exception('Image uploader: No file provided.');
        }
    }

    /**
     * Set the default resize values for encoding.
     *
     * @param int $width
     * @param int|null $height
     * @param bool $force
     */
    protected function setDefaultResize($width, $height = null, $force = false)
    {
        if ((is_null($this->resizeWidth) && is_null($this->resizeHeight) || $force)) {
            $this->resizeWidth = $width;
            $this->resizeHeight = $height;
        }
    }

    /**
     * Encode the uploaded image and get the resource handle.
     *
     * @param bool $autoScale
     * @return \Intervention\Image\Image
     */
    protected function encodeImage($autoScale = true)
    {
        return $this->encoder
            ->resize($this->resizeWidth, $this->resizeHeight)
            ->quality($this->quality)
            ->format($this->format)
            ->encode($this->file, $autoScale);
    }

    /**
     * Save the Fleet registration certificate
     *
     * @return string Returns the saved filename
     */
    public function saveFleetRegistrationCertificateImage()
    {
        $this->validateFile();

        $config = $this->config('fleets.upload.images');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        $image = $this->encodeImage();

        $filename = $this->hashGenerator->extension($this->format)->make();

        $filePath = file_path(data_get($config, 'path'), $filename);

        Storage::put($filePath, $image);

        return $filename;
    }
    

    /**
     * Save the Fleet vehicle back side image
     *
     * @return string Returns the saved filename
     */
    public function saveFleetBackSideImage()
    {
        $this->validateFile();

        $config = $this->config('fleets.upload.images');

        $this->setDefaultResize(data_get($config, 'image.store_resolution'));

        $image = $this->encodeImage();

        $filename = $this->hashGenerator->extension($this->format)->make();

        $filePath = file_path(data_get($config, 'path'), $filename);

        Storage::put($filePath, $image);

        return $filename;
    }

}
