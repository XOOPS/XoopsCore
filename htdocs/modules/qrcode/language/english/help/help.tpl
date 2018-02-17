<h4>Description</h4>
<p>
    The <strong>qrcode</strong> extension establishes a service provdier for the <em>Qrcode</em> service. This service allows modules to add QR Code (2D barcodes for mobile devices) output with minimal effort.
</p>
<h4>Installation</h4>
<p>
    The Qrcode extension installs like any other XOOPS extension. Only one Qrcode service provider can be used in XOOPS system at a time.
</p>
<h4>Preferences</h4>
<p>
    Qrcode's preferences allow you to establish the default QR code parameters. These parameter are:
    <ul>
        <li><strong>Error Correction Level</strong> - QR codes can be read even if some parts of the code are missing or damaged. The error correction level determines how much error correction information is stored in the code. Higher levels result in larger code sizes which are usually not needed for web display.</li>
        <li><strong>QR size</strong> - The pixel size of the generated QR code.</li>
        <li><strong>Image margin</strong> - Clear margin around the code in pixels. To read efficently, a QR code image needs to be surrounded by some clear space, a quiet zone.</li>
        <li><strong>Background Color</strong> - The background color of the QR code image can be adjusted. For readability, the background color should be <em>lighter</em> than the foreground color.</li>
        <li><strong>Foreground Color</strong> - The forground color of the QR code image can be adjusted. For best readability, the foreground color should be <em>darker</em> than background color, and result in a high contrast image.</li>
    </ul>
</p>
<h4>Notice</h4>
<p>
    <strong>QR Code&reg;</strong> is a registered trademark of Denso Wave Incorporated
</p>