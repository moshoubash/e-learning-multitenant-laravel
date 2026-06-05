{{--
  Shared CDN asset headers for the auth and app layouts.
  Subresource Integrity (SRI) hashes guarantee that the browser
  will refuse to execute a CDN response whose contents don't match
  the hash, mitigating CDN compromise / supply-chain injection.

  Mitigates OWASP A08:2021 - Software and Data Integrity Failures.
--}}
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap"
      rel="stylesheet"
      integrity="sha384-niUMpAoiVQxGCgijC6I6GMtyHty4Tkh2cNtwBi7gJArQpplGBpBseMI4UXMYaEG4"
      crossorigin="anonymous">

<link rel="stylesheet"
      href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css"
      integrity="sha384-AAXnU2kKXYuRYuSh+OM7GBGFCsWc9TpfBa88BSzlSjD2qx8cKgJBgAHdXO5/iNEz"
      crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"
        integrity="sha384-9nhczxUqK87bcKHh20fSQcTGD4qq5GhayNYSYWqwBkINBhOfQLg/P5HG5lF1urn4"
        crossorigin="anonymous"></script>
